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

    public function level($level)
    {
        return $this->andWhere(['level' => $level]);
    }

    public function category($category)
    {
        return $this->andWhere(['like', 'UPPER({{category}})', strtoupper($category)]);
    }

    public function logins()
    {
        return $this->level(\yii\log\Logger::LEVEL_INFO)
                ->category('user.login')
                ->orderBy(['id' => SORT_DESC]);
    }

    public function applicationSubmits()
    {
        return $this->level(\yii\log\Logger::LEVEL_INFO)
                ->category('user.application.submit')
                ->orderBy(['id' => SORT_DESC]);
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
