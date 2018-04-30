<?php

namespace app\models;

/**
 * This is the model class for table "prefectures_preference".
 *
 * @property integer $id
 * @property integer $prefect_id
 * @property integer $applicant_id
 * @property integer $school_type
 * @property integer $order
 *
 * @property Applicant $applicant
 * @property Prefecture $prefecture
 */
class PrefecturesPreference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prefectures_preference}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefect_id', 'applicant_id', 'order', 'school_type'], 'integer'],
            ['school_type', 'in', 'range' => [0, 1, 2]],
            [['order'], 'required'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['prefect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prefecture::className(), 'targetAttribute' => ['prefect_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prefect_id' => 'Περιφερειακή ενότητα',
            'applicant_id' => 'Αιτούσα/Αιτών',
            'school_type' => 'Τύπος σχολικής μονάδας',
            'order' => 'Σειρά προτίμησης',
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
    public function getPrefecture()
    {
        return $this->hasOne(Prefecture::className(), ['id' => 'prefect_id']);
    }

    public function getPrefectureName()
    {
        if ($this->prefecture) {
            return $this->prefecture->prefecture;
        } else {
            return null;
        }
    }
}
