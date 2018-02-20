<?php
namespace app\models;

/**
 * This is the model class for table "{{%audit_log}}".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class AuditLog extends \yii\db\ActiveRecord
{
    public $level_label;
    public $log_time_str;
    public $ip;
    public $user_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Επίπεδο',
            'category' => 'Κατηγορία',
            'log_time' => 'Χρονοσφραγίδα',
            'prefix' => 'Πρόθεμα',
            'message' => 'Μήνυμα καταγραφής',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        switch ($this->level) {
            case \yii\log\Logger::LEVEL_ERROR:
                $this->level_label = '<span class="label label-danger">error</span>';
                break;
            case \yii\log\Logger::LEVEL_INFO:
                $this->level_label = '<span class="label label-info">info</span>';
                break;
            case \yii\log\Logger::LEVEL_WARNING:
                $this->level_label = '<span class="label label-warning">warning</span>';
                break;
            case \yii\log\Logger::LEVEL_TRACE:
                $this->level_label = '<span class="text-info">trace</span>';
                break;
            default:
                $this->level_label = '<span class="label label-default">κάτι άλλο</span>';
                break;
        }

        $this->log_time_str = date("d-m-Y H:i:s", $this->log_time);
        $prefix_parts = explode('][', $this->prefix, 3);
        $this->ip = (isset($prefix_parts[0]) ? substr($prefix_parts[0], 1) : '-');
        $this->user_id = (isset($prefix_parts[1]) ? $prefix_parts[1] : '-');
    }

    /**
     * Fields to be returned in APIs
     */
    public function fields()
    {
        return [
            // 'id',
            'level',
            'category',
            'log_time',
            'prefix',
            'message',
            // 'o' => function ($model) {
            //     return [
            //         $this->level,
            //         $this->category,
            //         $this->log_time,
            //         $this->prefix,
            //         $this->message
            //     ];
            // }
        ];
    }

    /**
     * @inheritdoc
     * @return AuditLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuditLogQuery(get_called_class());
    }
}
