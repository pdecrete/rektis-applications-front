<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Choice]].
 *
 * @see Choice
 */
class ChoiceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Choice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Choice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
