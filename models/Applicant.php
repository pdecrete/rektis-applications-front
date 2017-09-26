<?php
namespace app\models;

use app\models\Choice;
use app\models\Application;

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
            'vat' => 'Vat',
            'identity' => 'Identity',
            'specialty' => 'Specialty',
        ];
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
