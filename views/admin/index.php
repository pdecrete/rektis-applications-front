<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Διαχειριστικές λειτουργίες';
$this->params['breadcrumbs'][] = $this->title;

?>

<h1>Διαχειριστικές λειτουργίες</h1>

<div class="body-content">

    <div class="row">
        <div class="col-lg-4">
            <h2>Εξαγωγή αιτήσεων</h2>
            <p>Η λειτουργία αυτή εξάγει όλες τις μη διεγραμμένες αιτήσεις σε μορφή CSV.</p>
            <p><?= Html::a(Html::icon('save') . ' Εξαγωγή σε CSV', Url::to(['admin/export-csv']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?></p>
        </div>
        <div class="col-lg-4">
            <h2>Επισκόπηση στοιχείων</h2>
            <p>Εμφάνιση συνοπτικών στατιστικών.</p>
            <p><?= Html::a('Προβολή', Url::to(['admin/overview']), ['class' => 'btn btn-primary']) ?></p>
        </div>
    </div>

</div>
