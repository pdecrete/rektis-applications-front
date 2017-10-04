<?php
namespace app\models;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $value
 * 
 */
class Config extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'value'], 'required'],
            [['name', 'type'], 'string', 'max' => 256],
            [['value'], 'string', 'max' => 512],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Configuration option name',
            'type' => 'Configuration option variable type',
            'value' => 'Configuration option value',
        ];
    }

    /**
     * @inheritdoc
     * @return ConfigtQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigQuery(get_called_class());
    }

    public static function getConfig($name, $default = null)
    {
        $query = new ConfigQuery(get_called_class());
        $query->andFilterWhere(['name' => $name]);

        $config = $query->one();
        if ($config) {
            switch ($config->type) {
                case 'int':
                    $value = intval($config->value);
                    break;
                case 'boolean':
                    $value = boolval($config->value);
                    break;
                case 'string':
                default:
                    $value = $config->value; // string
                    break;
            }
        } else {
            $value = $default;
        }
        return $value;
    }
}
