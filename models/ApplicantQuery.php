<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Applicant]].
 *
 * @see Applicant
 */
class ApplicantQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Applicant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Applicant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
