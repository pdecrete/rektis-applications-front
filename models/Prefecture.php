<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prefecture".
 *
 * @property integer $id
 * @property string $region
 * @property string $prefecture
 * @property string $reference
 *
 * @property Choice[] $choices
 * @property PrefecturesPreference[] $prefecturesPreferences
 */
class Prefecture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prefecture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefecture', 'reference'], 'required'],
            [['reference'], 'string'],
            [['region', 'prefecture'], 'string', 'max' => 150],
            [['prefecture'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region' => 'Region',
            'prefecture' => 'Prefecture',
            'reference' => 'Reference',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChoices()
    {
        return $this->hasMany(Choice::className(), ['prefecture_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrefecturesPreferences()
    {
        return $this->hasMany(PrefecturesPreference::className(), ['prefect_id' => 'id']);
    }
}
