<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση δήλωσης άρνησης αίτησης';
$this->params['breadcrumbs'][] = $this->title;

?>
    <h1>Άρνηση υποβολής αίτησης</h1>
    <div class="text-left">
        <?= $this->render('_applicant_info_header', ['user' => $user]) ?>
        <?= $this->render('_info_denial') ?>
    </div>
<div class="jumbotron">
    <p><?= Html::a('Eίμαι βέβαιος / βέβαιη', ['deny'], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Η δήλωση άρνησης υποβολής αίτησης είναι μη αναστρέψιμη ενέργεια. Είστε απόλυτα βέβαιοι;']) ?></p>
    <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
</div>
