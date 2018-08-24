<?php

namespace app\models;

/**
 * This is the model class for table "choice".
 *
 * @property integer $id
 * @property string $specialty
 * @property integer $count
 * @property string $position
 * @property integer $school_type
 * @property string $reference
 *
 * @property Application[] $applications
 */
class Choice extends \yii\db\ActiveRecord
{
    const SCHOOL_TYPE_DEFAULT = 1;
    const SCHOOL_TYPE_KEDDY = 2;

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
            [['specialty', 'count', 'position', 'reference', 'prefecture_id'], 'required'],
            [['count', 'school_type'], 'integer'],
            ['school_type', 'in', 'range' => [1, 2]],
            [['reference'], 'string'],
            [['specialty'], 'string', 'max' => 8],
            [['position'], 'string', 'max' => 150],
            [['specialty', 'position'], 'unique', 'targetAttribute' => ['specialty', 'position'], 'message' => 'The combination of Specialty and Position has already been taken.'],
            [['prefecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prefecture::className(), 'targetAttribute' => ['prefecture_id' => 'id']],
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
            'school_type' => 'Τύπος σχολικής μονάδας',
            'reference' => 'Αναφορά προγραμματιστή',
            'prefecture_id' => 'Νομός'
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
     * @return \yii\db\ActiveQuery
     */
    public function getPrefecture()
    {
        return $this->hasOne(Prefecture::className(), ['id' => 'prefecture_id']);
    }

    /**
     * @inheritdoc
     * @return ChoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChoiceQuery(get_called_class());
    }

    /**
     * Get a list of all choices matching the denoted filters.
     * 
     * @param int $prefecture_id
     * @param string $specialty
     * @param int $filter_school_type If not 0, used to filter school type.
     * @param string $filter_name Is set, require its value to be part of the position field.
     * @param int[] $filter_exclude_ids If set, EXCLUDES specified ids. 
     * @param boolean $get_query If true, return only the query object and not the data; If false, returns the activerecords result set.
     * @return ActiveQuery|ActiveRecord[]
     */
    public static function getChoices($prefecture_id, $specialty, $filter_school_type = 0, $filter_name = null, $filter_exclude_ids = null, $get_query = false)
    {
        $aq = static::find()->where(['prefecture_id' => $prefecture_id, 'specialty' => $specialty]);
        if (intval($filter_school_type) !== 0) {
            $aq->andWhere(['school_type' => $filter_school_type])
                ->orderBy(['school_type' => SORT_ASC]);
        }
        if (!empty($filter_name)) {
            $aq->andWhere(['like', 'position', $filter_name])
                ->orderBy(['position' => SORT_ASC]);
        }
        if (!empty($filter_exclude_ids)) {
            $aq->andWhere(['not', ['id' => $filter_exclude_ids]]);
        }
        return ($get_query === false) ? $aq->all() : $aq;
    }

    public static function schooltypeLabel($school_type)
    {
        switch ($school_type) {
            case 0:
                $label = 'ΟΠΟΙΟΣΔΗΠΟΤΕ ΤΥΠΟΣ ΜΟΝΑΔΑΣ';
                break;
            case self::SCHOOL_TYPE_DEFAULT:
                $label = 'ΣΧΟΛΙΚΕΣ ΜΟΝΑΔΕΣ';
                break;
            case self::SCHOOL_TYPE_KEDDY:
                $label = 'ΚΕ.Δ.Δ.Υ.';
                break;
            default:
                $label = 'άγνωστο';
                break;
        }
        return $label;
    }
}
