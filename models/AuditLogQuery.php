<?php
namespace app\models;

/**
 *
 * @see AuditLog
 */
class AuditLogQuery extends \yii\db\ActiveQuery
{

    public function withUserId($id)
    {
        return $this->andWhere(['like', 'prefix', "[" . intval($id) . "]"]);
    }

    /**
     * @inheritdoc
     * @return AuditLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuditLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
