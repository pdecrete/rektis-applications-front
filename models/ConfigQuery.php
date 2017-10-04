<?php
namespace app\models;

/**
 * This is the ActiveQuery class for [[Config]].
 *
 * @see Config
 */
class ConfigQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return Config[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Config|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
