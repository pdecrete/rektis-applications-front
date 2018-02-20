<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Διαχειριστικές λειτουργίες';
$this->params['breadcrumbs'][] = $this->title;

?>

    <h1>Διαχειριστικές λειτουργίες</h1>

    <div class="well">
        <div class="row">
            <div class="col-sm-4">
                Υποβολή αιτήσεων<br/>
                <?php if ($enable_applications === true) : ?>
                    <span class="label label-success">Ενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ban-circle'), ['enable/confirm-enable'], ['class' => 'btn btn-xs btn-danger', 'data-method' => 'POST', 'title' => 'Απενεργοποίηση']) ?>
                <?php else: ?>
                    <span class="label label-danger">Απενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ok-sign'), ['enable/confirm-enable'], ['class' => 'btn btn-xs btn-success', 'data-method' => 'POST', 'title' => 'Ενεργοποίηση']) ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                Διαλειτουργικότητα - φόρτωση στοιχείων<br/>
                <?php if ($enable_data_load === true) : ?>
                    <span class="label label-success">Ενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ban-circle'), ['enable/disable-data-load'], ['class' => 'btn btn-xs btn-danger', 'data-method' => 'POST', 'title' => 'Απενεργοποίηση']) ?>
                <?php else: ?>
                    <span class="label label-danger">Απενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ok-sign'), ['enable/enable-data-load'], ['class' => 'btn btn-xs btn-success', 'data-method' => 'POST', 'title' => 'Ενεργοποίηση']) ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                Διαλειτουργικότητα - κατέβασμα στοιχείων<br/>
                <?php if ($enable_date_unload === true) : ?>
                    <span class="label label-success">Ενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ban-circle'), ['enable/disable-data-unload'], ['class' => 'btn btn-xs btn-danger', 'data-method' => 'POST', 'title' => 'Απενεργοποίηση']) ?>
                <?php else: ?>
                    <span class="label label-danger">Απενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ok-sign'), ['enable/enable-data-unload'], ['class' => 'btn btn-xs btn-success', 'data-method' => 'POST', 'title' => 'Ενεργοποίηση']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-3">
                <h3>Επισκόπηση στοιχείων</h3>
                <p>Εμφάνιση συνοπτικών στατιστικών.</p>
                <p>
                    <?= Html::a('Προβολή', Url::to(['admin/overview']), ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
            <div class="col-lg-3">
                <h3>Προβολή Αιτήσεων</h3>
                <p>Προβολή αιτήσεων που έχουν υποβληθεί.</p>
                <p>
                    <?= Html::a('Προβολή', Url::to(['admin/view-applications']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </p>
            </div>
            <?php if ($admin) : ?>
            <div class="col-lg-3">
                <h3>Εξαγωγή αιτήσεων</h3>
                <p>Η λειτουργία αυτή εξάγει όλες τις μη διεγραμμένες αιτήσεις σε μορφή CSV.</p>
                <p>
                    <?= Html::a(Html::icon('save') . ' Εξαγωγή σε CSV', Url::to(['admin/export-csv']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                </p>
            </div>
            <?php endif; ?>
            <div class="col-lg-3">
                <h3>Εκτύπωση αιτήσεων</h3>
                <p>Η λειτουργία αυτή εκτυπώνει όλες τις υποβληθείσες αιτήσεις.</p>
                <p>
                    <?= Html::a(Html::icon('print') . ' Εκτύπωση', Url::to(['admin/print-applications']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <h3>Διαθέσιμοι Υποψήφιοι</h3>
                <p>Προβολή υποψηφηφίων εκπαιδευτικών που βρίσκονται στη βάση και αναμένεται να δηλώσουν τοποθέτηση ή άρνηση
                    τοποθέτησης.
                </p>
                <p>
                    <?= Html::a('Προβολή', Url::to(['view-candidates']), ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
            <div class="col-lg-3">
                <h3>Δηλώσεις Άρνησης</h3>
                <p>Προβολή εκπαιδευτικών που έχουν δηλώσει άρνηση τοποθέτησης.</p>
                <p>
                    <?= Html::a('Προβολή', Url::to(['view-denials']), ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
            <div class="col-lg-3">
                <h3>Εκτύπωση Δηλώσεων Άρνησης</h3>
                <p>Εκτύπωση όλων των δηλώσεων άρνησης τοποθέτησης.</p>
                <p>
                    <?= Html::a('Εκτύπωση', Url::to(['print-denials']), ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
        </div>

        <div class="row">
            <?php if ($admin) : ?>
            <div class="col-lg-3">
                <h3>Ενεργοποίηση αιτήσεων</h3>
                <?php if ($enable_applications === true) : ?>
                <p>Η υποβολή των αιτήσεων είναι
                    <strong>
                        <span class="text-success">ενεργοποιημένη.</span>
                    </strong>.</p>
                <p>
                    <?= Html::a('Απενεργοποίηση', Url::to(['enable/confirm-enable']), ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?>
                </p>
                <?php else: ?>
                <p>Η υποβολή των αιτήσεων είναι
                    <strong>
                        <span class="text-danger">απενεργοποιημένη.</span>
                    </strong>
                </p>
                <p>
                    <?= Html::a('Ενεργοποίηση', Url::to(['enable/confirm-enable']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if ($admin) : ?>
            <div class="col-lg-3">
                <h3>Εκκαθάριση στοιχείων</h3>
                <p>Η επιλογή αυτή θα διαγράψει όλα τα δεδομένα που αφορούν
                    <strong>αιτήσεις</strong>,
                    <strong>αιτούντες</strong>,
                    <strong>κενά</strong>,
                    <strong>περιφερειακές ενότητες</strong> και
                    <strong>στοιχεία καταγραφής ενεργειών χρηστών</strong>.</p>
                <p class="text-danger">Προσοχή! Η ενέργεια αυτή είναι
                    <strong>μή αναστρέψιμη</strong>!</p>
                <p>
                    <?= Html::a(Html::icon('trash') . ' Εκκαθάριση', Url::to(['admin/clear-data']), ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Η ενέργεια αυτή είναι μη αναστρέψιμη! Είστε βέβαιοι;']) ?>
                </p>
            </div>
            <?php endif; ?>
            <div class="col-lg-3">
                <h3>Αρχείο καταγραφής</h3>
                <p>Στοιχεία καταγραφής ενεργειών χρηστών.</p>
                <p class="text-info">Προβάλλονται στοιχεία καταγραφής που έχουν αποθηκευτεί στη βάση δεδομένων. Περισσότερες λεπτομέρειες και
                    καταγραφές γίνονται στα αρχεία καταγραφής.</p>
                <p>
                    <?= Html::a(Html::icon('eye-open') . ' Προβολή', Url::to(['audit-log/index']), ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
            <div class="col-lg-3">
                <h3>Κείμενα</h3>
                <p>Κείμενα που χρησιμοποιούνται στην εφαρμογή.</p>
                <p>
                    <?= Html::a(Html::icon('file') . ' Προβολή', Url::to(['page/index']), ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
        </div>
    </div>