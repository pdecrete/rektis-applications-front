<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση δήλωσης άρνησης αίτησης';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jumbotron">
    <h1>Επιβεβαίωση αποδοχής όρων</h1>
    <p>Παρακαλούμε μελετήστε προσεκτικά τις παρακάτω οδηγίες και όρους χρήσης.<p>
    <p><strong>Για να συνεχίσετε πρέπει να τις αποδεχτείτε.</strong><p>
</div>
<div class="well">
    <?= $this->render('_terms') ?>
</div>
<div class="jumbotron">
    <p>Πατήστε το κουμπί αποδέχομαι εφόσον μελετήσατε και συμφωνείτε με τα παραπάνω.<p>
    <p><?= Html::a('Αποδέχομαι', ['terms-agree'], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?></p>
    <p><?= Html::a('Άκυρο', ['/site/logout'], ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?></p>
</div>
