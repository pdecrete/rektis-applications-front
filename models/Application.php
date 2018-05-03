<?php
namespace app\models;

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
        return '{{%application}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicant_id', 'choice_id', 'order', 'updated', 'deleted'], 'integer'],
            [['applicant_id', 'choice_id', 'order'], 'required'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [
                ['choice_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Choice::className(),
                'targetAttribute' => ['choice_id' => 'id'],
                'filter' => function ($query) {
                    // check on submitted choice violation; must much applicant specialty
                    $query->andWhere(['specialty' => $this->applicant->specialty]);
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'applicant_id' => 'Αιτούσα/Αιτών',
            'choice_id' => 'Επιλογή',
            'order' => 'Σειρά προτίμησης',
            'updated' => 'Ενημέρωση',
            'deleted' => 'Διαγραφή',
        ];
    }

    /**
     * Fields to be returned in APIs
     */
    public function fields()
    {
        return [
            // 'id',
            'applicant_id',
            'choice_id',
            'order',
            'updated',
            'deleted',
            // 'applicant',
            // 'choice'
        ];
    }


    /**
     *
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->updated = new \yii\db\Expression('UNIX_TIMESTAMP()');
        return true;
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
