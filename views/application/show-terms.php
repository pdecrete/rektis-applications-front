<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση δήλωσης άρνησης αίτησης';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jumbotron">
    <h1>Επιβεβαίωση αποδοχής όρων</h1>
    <p>Παρακαλώ επιβεβαιώστε ότι αποδέχεστε τους όρους ....</p>
    <p><?= Html::a('Αποδέχομαι', ['terms-agree'], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?></p>
    <p><?= Html::a('Άκυρο', ['/site/logout'], ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?></p>
</div>
