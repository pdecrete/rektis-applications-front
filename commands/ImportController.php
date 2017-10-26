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

    public $defaultAction = 'parse';

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
     * Prefecture preference, Lastname, Firstname, Fathername, Mothername, Phone, VAT, ID card, email
     */
    public $fields = '1,2,3,4,5,6,9,11,12';

    /**
     * @var map associate ids to attributes
     */
    private $_field_mapping = [
        'preferences',
        'lastname',
        'firstname',
        'fathername',
        'mothername',
        'mobile',
        'vat',
        'identity',
        'email'
    ];

    public function options($actionID)
    {
        return [
            'runFixture',
            'csvDelimiter',
            'csvEnclosure',
            'csvSkipLines',
            'fields'
        ];
    }

    /**
     * TODO
     *
     * @param string $text_to_crypt the text to be crypted.
     */
    public function actionParse($file)
    {
        $this->stdout("Initiating data parse and import file generation...\n", Console::BOLD);

        $csv = Reader::createFromPath($file, 'r')
            ->setDelimiter($this->csvDelimiter)
            ->setEnclosure($this->csvEnclosure);
        //            ->setHeaderOffset($this->csvSkipLines - 1);

        $retrieve_statement = (new Statement())
            ->offset($this->csvSkipLines);

        $fields = explode(',', $this->fields);
        if (count($fields) !== count($this->_field_mapping)) {
            $this->stderr('Field count does not match', Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        $fields_idx = array_combine($fields, $fields);
        $common_ref = \Yii::$app->crypt->encrypt(json_encode(['sid' => -1]));
        $data = [
            'applicant' => [],
            'specialty' => ['ΕΒΠ'],
            'prefecture' => [
                ['id' => 1, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΗΡΑΚΛΕΙΟΥ', 'reference' => $common_ref],
                ['id' => 2, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΛΑΣΙΘΙΟΥ', 'reference' => $common_ref],
                ['id' => 3, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΡΕΘΥΜΝΟΥ', 'reference' => $common_ref],
                ['id' => 4, 'region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΧΑΝΙΩΝ', 'reference' => $common_ref],
            ],
            'prefecture_preference' => []
        ];

        $aid = 100;
        foreach ($retrieve_statement->process($csv) as $record) {
            $aid++;
            $mapped_record = array_combine($this->_field_mapping, array_intersect_key($record, $fields_idx));
            //            echo print_r($record, true), PHP_EOL;
            //            echo print_r($mapped_record, true), PHP_EOL;
            $applicant_data_entry = [
                'id' => $aid, // keep the id
                'vat' => $mapped_record['vat'],
                'identity' => $mapped_record['identity'],
                'specialty' => 'ΕΒΠ',
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
        $filename = dirname($file) . '/data.serialized.txt';
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
}
