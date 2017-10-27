<?php
namespace app\components;

use yii\base\ActionFilter;
use app\models\Applicant;

class TermsAgreement extends ActionFilter
{
    public function beforeAction($action)
    {
        $parentBeforeAction = parent::beforeAction($action);

        if (!$parentBeforeAction) {
            return false;
        }

        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if ($user->agreedterms == null) {
            \Yii::$app->response->redirect(['application/request-agree']);
        }

        return true;
    }
}
