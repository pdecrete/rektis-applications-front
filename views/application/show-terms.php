<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση ενημέρωσης για τη διαδικασία υποβολής αιτήσεων';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jumbotron">
    <h2>Επιβεβαίωση ενημέρωσης για τη διαδικασία υποβολής αιτήσεων</h2>
    <p>Παρακαλούμε μελετήστε προσεκτικά τις παρακάτω οδηγίες.</p>
    <p><strong>Για να συνεχίσετε πρέπει να τις αποδεχτείτε.</strong></p>
</div>
<div class="well">
    <?= $this->render('_info_terms') ?>
</div>
<div class="jumbotron">
    <p>Πατήστε το κουμπί αποδέχομαι εφόσον μελετήσατε και συμφωνείτε με τα παραπάνω.<p>
    <p><?= Html::a('Αποδέχομαι', ['terms-agree'], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?></p>
    <p><?= Html::a('Δεν αποδέχομαι', ['/site/logout'], ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?></p>
</div>
