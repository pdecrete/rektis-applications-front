<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property integer $id
 * @property integer $applicant_id
 * @property integer $choice_id
 * @property integer $order
 * @property integer $updated
 * @property integer $deleted
 *
 * @property Applicant $applicant
 * @property Choice $choice
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicant_id', 'choice_id', 'order', 'updated', 'deleted'], 'integer'],
            [['order', 'updated'], 'required'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Choice::className(), 'targetAttribute' => ['choice_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'applicant_id' => 'Applicant ID',
            'choice_id' => 'Choice ID',
            'order' => 'Order',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChoice()
    {
        return $this->hasOne(Choice::className(), ['id' => 'choice_id']);
    }

    /**
     * @inheritdoc
     * @return ApplicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplicationQuery(get_called_class());
    }
}
