<?php

namespace app\models;

/**
 * This is the model class for table "choice".
 *
 * @property integer $id
 * @property string $specialty
 * @property integer $count
 * @property string $position
 * @property string $reference
 *
 * @property Application[] $applications
 */
class Choice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%choice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['specialty', 'count', 'position', 'reference'], 'required'],
            [['count'], 'integer'],
            [['reference'], 'string'],
            [['specialty'], 'string', 'max' => 8],
            [['position'], 'string', 'max' => 150],
            [['specialty', 'position'], 'unique', 'targetAttribute' => ['specialty', 'position'], 'message' => 'The combination of Specialty and Position has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'specialty' => 'Ειδικότητα',
            'count' => 'Αριθμός κενών',
            'position' => 'Θέση',
            'reference' => 'Αναφορά προγραμματιστή',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['choice_id' => 'id']);
    }


    /**
     * @inheritdoc
     * @return ChoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChoiceQuery(get_called_class());
    }

    public static function getChoices($prefecture_id, $specialty)
    {
        return static::find()->where(['prefecture_id' => $prefecture_id, 'specialty' => $specialty])->all();
    }
}
