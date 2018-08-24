<?php
namespace app\models;

/**
 * This is the model class for table "{{%applicant}}".
 *
 * @property integer $id
 * @property string $vat
 * @property string $identity
 * @property string $specialty
 * @property int $state
 *
 * @property Application[] $applications
 */
class Applicant extends \yii\db\ActiveRecord
{
    const DENIED_TO_APPLY = "1";

    public $last_submit_str;
    public $has_submitted; // denote if the applicant has made a submission at some time
    public $state_ts_str;
    /* the following properties should be separated from the model in th final version */
    public $firstname;
    public $lastname;
    public $fathername;
    public $email;
    public $phone;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%applicant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vat', 'identity', 'specialty'], 'required'],
            [['vat'], 'string', 'max' => 9],
            ['state', 'in', 'range' => [0, 1]],
            [['identity'], 'string', 'max' => 32],
            [['specialty'], 'string', 'max' => 8],
            [['vat'], 'unique'],
            [['identity'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vat' => 'Α.Φ.Μ.',
            'identity' => 'Α.Δ.Τ.',
            'specialty' => 'Ειδικότητα',
            'last_submit_str' => 'Υποβολή αίτησης',
            'state_ts_str' => 'Αλλαγή κατάστασης (άρνησης)',
        ];
    }

    /**
     * Fields to be returned in APIs
     */
    public function fields()
    {
        return [
            'id',
            'reference',
            'agreedterms' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)$model->agreedterms);
            },
            'vat' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)$model->vat);
            },
            'identity' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)$model->identity);
            },
            'specialty' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)$model->specialty);
            },
            'application_choices' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)count($model->applications));
            },
            'state' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)$model->state);
            },
            'statets' => function ($model) {
                return \Yii::$app->crypt->encrypt((string)$model->statets);
            },
        ];
    }

    public function afterFind()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->fathername = '';
        $this->email = '';
        $this->phone = '';

        try {
            $reference = \Yii::$app->crypt->decrypt($this->reference);
            $more_data = json_decode($reference, true);
            if ($more_data) {
                foreach (['firstname', 'lastname', 'fathername','email', 'phone'] as $fieldname) {
                    $this->$fieldname = isset($more_data[$fieldname]) ? $more_data[$fieldname] : '-';
                }
            }
        } catch (Exception $e) {
            // leave unhandled; TODO log
            throw $e;
        }

        $last_submit_model = AuditLog::find()->withUserId($this->id)->applicationSubmits()->one();
        if (empty($last_submit_model)) {
            $this->last_submit_str = '<span class="label label-default">Δεν εντοπίστηκε</span>';
            $this->has_submitted = false;
        } else {
            $this->last_submit_str = "<strong>{$last_submit_model->log_time_str}</strong>";
            $this->has_submitted = true; 
        }

        if (empty($this->statets)) {
            $this->state_ts_str = null;
        } else {
            $this->state_ts_str =  date("d-m-Y H:i:s", $this->statets);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChoices()
    {
        return $this->hasMany(Choice::className(), ['specialty' => 'specialty']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['applicant_id' => 'id'])
                ->andOnCondition(['deleted' => 0])
                ->orderBy(['order' => 'ASC']);
    }

    /**
     * @inheritdoc
     * @return ApplicantQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplicantQuery(get_called_class());
    }
}
