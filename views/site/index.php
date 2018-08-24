<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use app\models\Applicant;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

if (!isset($has_applications)) {
    $has_applications = false;
}

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
            <?php if ($has_applications) : ?>
                <h2>Επεξεργασία</h2>
                <p>Επεξεργαστείτε την αίτηση που έχετε υποβάλλει</p>
                <?php if ($enable_applications === true) : ?>
                    <p><?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Επεξεργασία', Url::to(['application/apply']), ['class' => 'btn btn-success']) ?></p>
                <?php else: ?>
                    <p><?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Επεξεργασία', '#', ['class' => 'btn btn-success disabled', 'disabled' => 'disabled']) ?></p>
                    <p class="text-danger">Η υποβολή αιτήσεων δεν είναι διαθέσιμη.</p>
                <?php endif; ?>
            <?php else: ?>
                <h2>Καταχώρηση</h2>
                <p>Υποβάλλετε την αίτησή σας εδώ.</p>
                <?php if ($enable_applications === true) : ?>
                    <p><?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Υποβολή', Url::to(['application/apply']), ['class' => 'btn btn-success']) ?></p>
                <?php else: ?>
                    <p><?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Υποβολή', '#', ['class' => 'btn btn-success disabled', 'disabled' => 'disabled']) ?></p>
                    <p class="text-danger">Η υποβολή αιτήσεων δεν είναι διαθέσιμη.</p>
                <?php endif; ?>
            <?php endif; ?>
            </div>
            <div class="col-lg-4">
            <?php if ($has_applications) : ?>
                <h2>Προβολή</h2>
                <p>Προβάλλετε την αίτηση σας εδώ.</p>
                <p><?= Html::a('<span class="glyphicon glyphicon-file"></span> Προβολή', Url::to(['application/my-application']), ['class' => 'btn btn-primary']) ?></p>
            <?php endif; ?>
            </div>
            <div class="col-lg-4">
            <?php if ($has_applications) : ?>
                <h2>Διαγραφή αίτησης</h2>
                <p>Ακυρώστε την αίτηση - δήλωση διαγράφοντας την οριστικά.</p>
                <p><?= Html::a('<span class="glyphicon glyphicon-trash"></span> Διαγραφή', Url::to(['application/delete-my-application']), ['class' => 'btn btn-danger']) ?></p>
            <?php else : ?>
                <h2>Υποβολή αρνητικής δήλωσης</h2>
                <p>Υποβάλλετε δήλωση άρνησης πρόσληψης εδώ.</p>
                <p><?= Html::a('<span class="glyphicon glyphicon-remove"></span> Αρνητική δήλωση', Url::to(['application/request-deny']), ['class' => 'btn btn-primary']) ?></p>
            <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <h2>Αποσύνδεση</h2>
                <p>Αποσυνδεθείτε από την εφαρμογή.</p>
                <p><?= Html::a('<span class="glyphicon glyphicon-off"></span> Αποσύνδεση', ['logout'], ['class' => 'btn btn-default', 'data-method' => 'POST']) ?></p>
            </div>
        </div>
    </div>
</div>
