<?php
namespace app\commands;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use League\Csv\Reader;
use League\Csv\Statement;

/**
 * TODO
 *
 * @author Stavros Papadakis <spapad@gmail.com>
 * @since 2.0
 */
class ImportController extends Controller
{

    public $defaultAction = 'load';

    /**
     * @var string If "yes" the command also invokes the necessary fixture load command
     */
    public $runFixture = 'no';

    /**
     * @var string CSV delimiter character
     */
    public $csvDelimiter = ';';

    /**
     * @var string CSV enclosure character
     */
    public $csvEnclosure = '"';

    /**
     * @var string How many line to skip to get to the actual data
     */
    public $csvSkipLines = 1;

    /**
     * @var string Numeric index of the fields to retrieve. In order:
     * For the applicants: 
     * Specialty, Prefecture preference, Lastname, Firstname, Fathername, Mothername, Phone, VAT, ID card, email
     */
    public $fields_applicants = '1,2,3,4,5,6,7,10,12,13';

    /**
     * @var string Numeric index of the fields to retrieve. In order:
     * For the choices:
     * Specialty, Position count, Position description, Prefecture (only the first letter in capital)
     */
    public $fields_choices = '0,1,2,3';

    /**
     * @var map associate ids to attributes
     */
    private $_field_mapping = [
        'applicants' => [
            'specialty',
            'preferences',
            'lastname',
            'firstname',
            'fathername',
            'mothername',
            'mobile',
            'vat',
            'identity',
            'email'
        ],
        'choices' => [
            'specialty',
            'count',
            'position',
            'prefecture'
        ]
    ];

    public function options($actionID)
    {
        return [
            'runFixture',
            'csvDelimiter',
            'csvEnclosure',
            'csvSkipLines',
            'fields_applicants',
            'fields_choices',
        ];
    }

    /**
     * Check the data contained in a serialized data file and display on stdout.
     * 
     * @param string $serialized_file The file containing the serialized data
     */
    public function actionShow($serialized_file)
    {
        $serialized_data = file_get_contents($serialized_file);
        $data = unserialize($serialized_data);
        echo print_r($data, true), PHP_EOL;
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Parse and generate serialized data files with the applicant and choices data included in CSV files.
     *
     * @param string $applicants_file the filename of the CSV containing the applicant information
     * @param string $choices_file the filename of the CSV containing the choices information
     */
    public function actionParse($applicants_file, $choices_file)
    {
        $this->stdout("Initiating data parse and import file generation...\n", Console::BOLD);
        $this->stdout("Getting choices data...\n", Console::BOLD);
        $data1 = $this->getApplicantData($applicants_file);
        if (!is_array($data1)) {
            return $data1;
        }

        $this->stdout("Getting applicant data...\n", Console::BOLD);
        $data2 = $this->getChoicesData($choices_file);
        if (!is_array($data2)) {
            return $data2;
        }

        $data = [
            'applicant' => $data1['applicant'],
            'specialty' => array_unique(array_merge($data1['specialty'], $data1['specialty'])),
            'prefecture' => $this->getPrefecturesData(),
            'choices' => $data2['choices'],
            'prefecture_preference' => $data1['prefecture_preference']
        ];

        $filename = $this->getOutputFilename($applicants_file);
        if (($saved = file_put_contents($filename, serialize($data))) === false) {
            $this->stderr("Cannot save serialized data to file {$filename}\n", Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        $this->stdout("Data parsed and saved to import file {$filename}\n", Console::BOLD);

        if (strtoupper($this->runFixture) === 'YES') {
            $this->stdout("Running fixture load command\n", Console::BOLD);
            Yii::setAlias('@import-data-dir', Yii::getAlias('@app/commands/data-files'));
            $run_fixture = \Yii::$app->runAction('fixture/load', ['Application', 'namespace' => 'app\tests\importFixtures']);
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Parse and generate serialized data files with the applicant data included in a CSV file.
     *
     * @param string $file the filename of the CSV containing the applicant information
     */
    public function actionParseApplicants($file)
    {
        $this->stdout("Initiating data parse and import file generation...\n", Console::BOLD);

        $data = $this->getApplicantData($file);
        if (!is_array($data)) {
            return $data;
        }

        $filename = $this->getOutputFilename($file);
        if (($saved = file_put_contents($filename, serialize($data))) === false) {
            $this->stderr("Cannot save serialized data to file {$filename}\n", Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        $this->stdout("Data parsed and saved to import file {$filename}\n", Console::BOLD);

        if (strtoupper($this->runFixture) === 'YES') {
            $this->stdout("Running fixture load command\n", Console::BOLD);
            Yii::setAlias('@import-data-dir', Yii::getAlias('@app/commands/data-files'));
            $run_fixture = \Yii::$app->runAction('fixture/load', ['Application', 'namespace' => 'app\tests\importFixtures']);
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Parse and generate serialized data file with the available choices data included in a CSV file.
     *
     * @param string $file the filename of the CSV containing the applicant choices
     */
    public function actionParseChoices($file)
    {
        $this->stdout("Initiating data parse and import file generation...\n", Console::BOLD);

        $data = $this->getChoicesData($file);
        if (!is_array($data)) {
            return $data;
        }
        $data['prefecture'] = $this->getPrefecturesData();

        $filename = $this->getOutputFilename($file);
        if (($saved = file_put_contents($filename, serialize($data))) === false) {
            $this->stderr("Cannot save serialized data to file {$filename}\n", Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        $this->stdout("Data parsed and saved to import file {$filename}\n", Console::BOLD);

        if (strtoupper($this->runFixture) === 'YES') {
            $this->stdout("Running fixture load command\n", Console::BOLD);
            Yii::setAlias('@import-data-dir', Yii::getAlias('@app/commands/data-files'));
            $run_fixture = \Yii::$app->runAction('fixture/load', ['Application', 'namespace' => 'app\tests\importFixtures']);
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    private function getOutputFilename($file)
    {
        return dirname($file) . '/data.serialized.txt';
    }

    private function getPrefecturesData()
    {
        $common_ref = \Yii::$app->crypt->encrypt(json_encode(['sid' => -1]));
        return [
            ['id' => 1, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΗΡΑΚΛΕΙΟΥ', 'reference' => $common_ref],
            ['id' => 2, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΛΑΣΙΘΙΟΥ', 'reference' => $common_ref],
            ['id' => 3, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΡΕΘΥΜΝΟΥ', 'reference' => $common_ref],
            ['id' => 4, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΧΑΝΙΩΝ', 'reference' => $common_ref],
        ];
    }

    /**
     * Returns APPLICANT, PREFECTURE_PREFERENCE and SPECIALTY filled
     */
    private function getApplicantData($file)
    {
        $csv = Reader::createFromPath($file, 'r')
            ->setDelimiter($this->csvDelimiter)
            ->setEnclosure($this->csvEnclosure);

        $retrieve_statement = (new Statement())
            ->offset($this->csvSkipLines);

        $fields = explode(',', $this->fields_applicants);
        if (count($fields) !== count($this->_field_mapping['applicants'])) {
            $this->stderr('Field count does not match', Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        $fields_idx = array_combine($fields, $fields);
        $data = [
            'applicant' => [],
            'specialty' => [],
            'prefecture' => [],
            'choices' => [],
            'prefecture_preference' => []
        ];

        $aid = 100;
        foreach ($retrieve_statement->process($csv) as $record) {
            $aid++;
            
            $mapped_record = array_combine($this->_field_mapping['applicants'], array_intersect_key($record, $fields_idx));
            $applicant_data_entry = [
                'id' => $aid, // keep the id
                'vat' => $mapped_record['vat'],
                'identity' => preg_replace('/\s+/', '', $mapped_record['identity']),
                'specialty' => preg_replace('/\s+/', '', $mapped_record['specialty']),
                'reference' => \Yii::$app->crypt->encrypt(
                    json_encode([
                    'firstname' => $mapped_record['firstname'],
                    'lastname' => $mapped_record['lastname'],
                    'fathername' => $mapped_record['fathername'],
                    'mothername' => $mapped_record['mothername'],
                    'email' => $mapped_record['email'],
                    'phone' => $mapped_record['mobile']
                    ])
                )
            ];
            $data['applicant'][] = $applicant_data_entry;
            // take care of preferences
            $preferences = array_filter(preg_split('//u', $mapped_record['preferences'], null, PREG_SPLIT_NO_EMPTY), function ($v) {
                return !empty($v);
            });
            foreach ($preferences as $idx => $pref) {
                switch (strtoupper($pref)) {
                    case 'Η':
                        $prefect_id = 1;
                        break;
                    case 'Λ':
                        $prefect_id = 2;
                        break;
                    case 'Ρ':
                        $prefect_id = 3;
                        break;
                    case 'Χ':
                        $prefect_id = 4;
                        break;
                    default:
                        $this->stderr("UNKNOWN PREFECTURE IDENTIFIER [{$pref}] [{$mapped_record['preferences']}]", Console::FG_RED);
                        return Controller::EXIT_CODE_ERROR;
                }
                $data['prefecture_preference'][] = [
                    'prefect_id' => $prefect_id,
                    'applicant_id' => $aid,
                    'order' => $idx + 1
                ];
            }
        }

        return $data;
    }

    /**
     * Returns CHOICES and SPECIALTY filled
     */
    private function getChoicesData($file)
    {
        $csv = Reader::createFromPath($file, 'r')
            ->setDelimiter($this->csvDelimiter)
            ->setEnclosure($this->csvEnclosure);

        $retrieve_statement = (new Statement())
            ->offset($this->csvSkipLines);

        $fields = explode(',', $this->fields_choices);
        if (count($fields) !== count($this->_field_mapping['choices'])) {
            $this->stderr('Field count does not match', Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        $fields_idx = array_combine($fields, $fields);
        $common_ref = \Yii::$app->crypt->encrypt(json_encode(['sid' => -1]));

        $data = [
            'applicant' => [],
            'specialty' => [],
            'prefecture' => [],
            'choices' => [],
            'prefecture_preference' => []
        ];

        $aid = 1;
        foreach ($retrieve_statement->process($csv) as $record) {
            $aid++;
            $mapped_record = array_combine($this->_field_mapping['choices'], array_intersect_key($record, $fields_idx));

            $pref = trim($mapped_record['prefecture']);
            switch (strtoupper($pref)) {
                case 'Η':
                    $prefect_id = 1;
                    break;
                case 'Λ':
                    $prefect_id = 2;
                    break;
                case 'Ρ':
                    $prefect_id = 3;
                    break;
                case 'Χ':
                    $prefect_id = 4;
                    break;
                default:
                    $this->stderr("UNKNOWN PREFECTURE IDENTIFIER [{$pref}] [{$mapped_record['preferences']}]", Console::FG_RED);
                    return Controller::EXIT_CODE_ERROR;
            }
            $applicant_data_entry = [
                'id' => $aid,
                'specialty' => preg_replace('/\s+/', '', $mapped_record['specialty']),
                'count' => $mapped_record['count'],
                'position' => $mapped_record['position'],
                'reference' => $common_ref,
                'prefecture_id' => $prefect_id
            ];

            $data['choices'][] = $applicant_data_entry;
            $data['specialty'][] = $mapped_record['specialty'];
        }
        $data['specialty'] = array_unique($data['specialty']);

        return $data;
    }
}
