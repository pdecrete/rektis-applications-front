<?php
namespace app\components;

use Yii;
use yii\base\ActionFilter;
use app\models\Applicant;
use app\controllers\SiteController;

class TermsAgreement extends ActionFilter
{
    public function beforeAction($action)
    {
        $parentBeforeAction = parent::beforeAction($action);
        
        if(!$parentBeforeAction) 
            return false;
        
        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if($user->agreedterms == NULL)
			\Yii::$app->response->redirect(['application/request-agree']);
        
        return true;    
    }
}
