<?php
namespace app\models;

/**
 * This is the model class for table "{{%applicant}}".
 *
 * @property integer $id
 * @property string $vat
 * @property string $identity
 * @property string $specialty
 *
 * @property Application[] $applications
 */
class Applicant extends \yii\db\ActiveRecord
{
    public $last_submit_str;
    /* the following properties should be separated from the model in th final version */
    public $firstname;
    public $lastname;
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
            'last_submit_str' => 'Υποβολή αίτησης'
        ];
    }

    public function afterFind()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->phone = '';

        try {
            $reference = \Yii::$app->crypt->decrypt($this->reference);
            $more_data = json_decode($reference, true);
            if ($more_data) {
                foreach (['firstname', 'lastname', 'email', 'phone'] as $fieldname) {
                    $this->$fieldname = isset($more_data[$fieldname]) ? $more_data[$fieldname] : '-';
                }
            }
        } catch (Exception $e) {
            // leave unhandled; TODO
            throw $e;
        }

        $last_submit_model = AuditLog::find()->withUserId($this->id)->applicationSubmits()->one();
        if ($last_submit_model !== null) {
            $this->last_submit_str = "<strong>{$last_submit_model->log_time_str}</strong>";
        } else {
            $this->last_submit_str = '<span class="label label-danger">Δεν εντοπίστηκε</span>';
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
