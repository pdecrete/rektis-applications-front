<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Καταχώρηση</h2>
                <p>Υποβάλλετε την αίτησή σας εδώ.</p>
                <p><?= Html::a('Υποβολή', Url::to(['application/apply']), ['class' => 'btn btn-success']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Προβολή</h2>
                <p>Προβάλλετε την αίτηση σας εδώ.</p>
                <p><?= Html::a('Προβολή', Url::to(['application/my-application']), ['class' => 'btn btn-primary']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Εξαγωγή</h2>
                <p>Η εξαγωγή είναι διαθέσιμη μόνο στους διαχειριστές.</p>
                <p><?= Html::a('Εξαγωγή', Url::to(['export/index']), ['class' => 'btn btn-info', 'data-method' => 'POST']) ?></p>
            </div>
        </div>

    </div>
</div>
