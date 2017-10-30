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
    <h1>Επιβεβαίωση άρνησης αίτησης</h1>
    <p>Παρακαλούμε μελετήστε προσεκτικά τους όρους της δήλωση άρνησης τοποθέτησης.<p>
    <p><strong>Για να συνεχίσετε πρέπει να τους αποδεχτείτε.</strong><p>
</div>
<div class="well">
    <?= $this->render('_denyterms') ?>
</div>
<div class="jumbotron">
    <p>Πατήστε το κουμπί αποδέχομαι εφόσον μελετήσατε και συμφωνείτε με τα παραπάνω.<p>
    <p><?= Html::a('Αποδέχομαι', ['deny'], ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?></p>

    <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
</div>

