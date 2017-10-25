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
            <h2>Ενεργοποίηση αιτήσεων</h2>
            <?php if ($enable_applications === true) : ?>
                <p>Η υποβολή των αιτήσεων είναι <strong><span class="text-success">ενεργοποιημένη.</span></strong>.</p>
                <p><?= Html::a('Απενεργοποίηση', Url::to(['enable-applications/confirm-enable']), ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?></p>
            <?php else: ?>
                <p>Η υποβολή των αιτήσεων είναι <strong><span class="text-danger">απενεργοποιημένη.</span></strong></p>
                <p><?= Html::a('Ενεργοποίηση', Url::to(['enable-applications/confirm-enable']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?></p>
            <?php endif; ?>
        </div>
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

    <div class="row">
        <div class="col-lg-4">
            <h2>Εκκαθάριση στοιχείων</h2>
            <p>Η επιλογή αυτή θα διαγράψει όλα τα δεδομένα που αφορούν <strong>αιτήσεις</strong>, <strong>αιτούντες</strong>, <strong>κενά</strong> και <strong>περιφερειακές ενότητες</strong>.</p>
            <p class="text-danger">Προσοχή! Η ενέργεια αυτή είναι <strong>μή αναστρέψιμη</strong>!</p>
            <p><?= Html::a(Html::icon('trash') . ' Εκκαθάριση', Url::to(['admin/clear-data']), ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Η ενέργεια αυτή είναι μη αναστρέψιμη! Είστε βέβαιοι;']) ?></p>
        </div>
        <div class="col-lg-4">
            <h2>Εκτύπωση αιτήσεων</h2>
            <p>Η λειτουργία αυτή εκτυπώνει όλες τις υποβληθείσες αιτήσεις.</p>
            <p><?= Html::a(Html::icon('print') . ' Εκτύπωση', Url::to(['admin/print-applications']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?></p>
        </div>        
        <div class="col-lg-4">
            <h2>Προβολή Αιτήσεων</h2>
            <p>Προβολή αιτήσεων που έχουν υποβληθεί.</p>
            <p><?= Html::a('Προβολή', Url::to(['admin/view-applications']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <h2>Αρχείο καταγραφής</h2>
            <p>Στοιχεία καταγραφής ενεργειών χρηστών.</p>
            <p class="text-info">Προβάλλονται στοιχεία καταγραφής που έχουν αποθηκευτεί στη βάση δεδομένων. Περισσότερες λεπτομέρειες και καταγραφές γίνονται στα αρχεία καταγραφής.</p>
            <p><?= Html::a(Html::icon('eye-open') . ' Προβολή', Url::to(['audit-log/index']), ['class' => 'btn btn-primary']) ?></p>
        </div>
    </div>
</div>
