<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Διαχειριστικές λειτουργίες';
$this->params['breadcrumbs'][] = $this->title;

?>

    <div class="well">
        <div class="row">
            <div class="col-sm-4">
                Υποβολή αιτήσεων
                <br/>
                <?php if ($enable_applications === true) : ?>
                <span class="label label-success">Ενεργοποιημένη</span>
                <?= Html::a(Html::icon('ban-circle'), ['enable/confirm-enable'], ['class' => 'btn btn-xs btn-danger', 'data-method' => 'POST', 'title' => 'Απενεργοποίηση']) ?>
                    <?php else: ?>
                    <span class="label label-danger">Απενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ok-sign'), ['enable/confirm-enable'], ['class' => 'btn btn-xs btn-success', 'data-method' => 'POST', 'title' => 'Ενεργοποίηση']) ?>
                        <?php endif; ?>
            </div>
            <div class="col-sm-4">
                Διαλειτουργικότητα - φόρτωση στοιχείων
                <br/>
                <?php if ($enable_data_load === true) : ?>
                <span class="label label-success">Ενεργοποιημένη</span>
                <?= Html::a(Html::icon('ban-circle'), ['enable/disable-data-load'], ['class' => 'btn btn-xs btn-danger', 'data-method' => 'POST', 'title' => 'Απενεργοποίηση']) ?>
                    <?php else: ?>
                    <span class="label label-danger">Απενεργοποιημένη</span>
                    <?= Html::a(Html::icon('ok-sign'), ['enable/enable-data-load'], ['class' => 'btn btn-xs btn-success', 'data-method' => 'POST', 'title' => 'Ενεργοποίηση']) ?>
                        <?php endif; ?>
            </div>
            <div class="col-sm-4">
                Διαλειτουργικότητα - κατέβασμα στοιχείων
                <br/>
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
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Στοιχεία</h3></div>
                    <div class="panel-body">

                        <p>
                            <?= Html::a(Html::icon('list') . ' Επισκόπηση', Url::to(['admin/overview']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Εμφάνιση συνοπτικών στατιστικών.</p>

                        <hr/>
                        <p>
                            <?= Html::a(Html::icon('list') . ' Υποψήφιοι', Url::to(['view-candidates']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Προβολή υποψηφηφίων εκπαιδευτικών που βρίσκονται στη βάση και αναμένεται να δηλώσουν τοποθέτηση ή
                            άρνηση τοποθέτησης.</p>

                        <hr/>
                        <p>
                            <?= Html::a(Html::icon('print') . ' Χωρίς αίτηση', Url::to(['admin/print-no-application-candidates']), ['class' => 'btn btn-info', 'data-method' => 'POST']) ?>
                        </p>
                        <p>Η λειτουργία αυτή εκτυπώνει τα στοιχεία των υποψήφιων εκπαιδευτικών που <strong>δεν</strong> έχουν 
                            υποβάλει αίτηση-δήλωση ούτε δήλωση άρνησης τοποθέτησης.</p>

                        <hr/>
                        <p>
                            <?= Html::a(Html::icon('list') . ' Κενά', Url::to(['view-choices']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Προβολή καταχωρημένων κενών για τις δηλώσεις.</p>

                        <hr/>
                        <?php if ($admin) : ?>
                        <p>
                            <?= Html::a(Html::icon('save') . ' Εισαγωγή περιοχών από CSV', Url::to(['admin/import-prefectures']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($admin) : ?>
                        <p>
                            <?= Html::a(Html::icon('save') . ' Εισαγωγή κενών από CSV', Url::to(['admin/import-choices']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($admin) : ?>
                        <p>
                            <?= Html::a(Html::icon('save') . ' Εισαγωγή αναπληρωτών από CSV', Url::to(['admin/import-teachers']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($admin) : ?>
                        <p>
                            <?= Html::a(Html::icon('save') . ' Εισαγωγή προτιμήσεων από CSV', Url::to(['admin/import-preferences']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <p>Οι παραπάνω λειτουργίες γεμίζουν τους 4 βασικούς πίνακες του frontend από .csv του backend για τη δυνατότητα υποβολής αιτήσεων.</p>
                        <?php endif; ?>
                        

                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Αιτήσεις</h3></div>
                    <div class="panel-body">
                        <p>
                            <?= Html::a(Html::icon('list') . ' Προβολή', Url::to(['admin/view-applications']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                        </p>
                        <p>Προβολή αιτήσεων που έχουν υποβληθεί.</p>

                        <hr/>
                        <p>
                            <?= Html::a(Html::icon('print') . ' Εκτύπωση', Url::to(['admin/print-applications']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <p>Η λειτουργία αυτή εκτυπώνει όλες τις υποβληθείσες αιτήσεις.</p>

                        <hr/>
                        <?php if ($admin) : ?>
                        <p>
                            <?= Html::a(Html::icon('save') . ' Εξαγωγή σε CSV', Url::to(['admin/export-csv']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <p>Η λειτουργία αυτή εξάγει όλες τις μη διεγραμμένες αιτήσεις σε μορφή CSV.</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Αρνητικές δηλώσεις</h3></div>
                    <div class="panel-body">
                        <p>
                            <?= Html::a(Html::icon('list') . ' Προβολή', Url::to(['view-denials']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Προβολή εκπαιδευτικών που έχουν δηλώσει άρνηση τοποθέτησης.</p>

                        <hr/>
                        <p>
                            <?= Html::a(Html::icon('print') . ' Εκτύπωση', Url::to(['print-denials']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Εκτύπωση όλων των δηλώσεων άρνησης τοποθέτησης.</p>

                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Διαχείριση</h3></div>
                    <div class="panel-body">
                        <?php if ($admin) : ?>

                        <?php if ($enable_applications === true) : ?>
                        <p>
                            <?= Html::a(Html::icon('cog') . ' Απενεργοποίηση αιτήσεων', Url::to(['enable/confirm-enable']), ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?>
                        </p>
                        <p>Η υποβολή των αιτήσεων είναι
                            <strong>
                                <span class="text-success">ενεργοποιημένη.</span>
                            </strong>
                        </p>
                        <?php else: ?>
                        <p>
                            <?= Html::a(Html::icon('cog') . ' Ενεργοποίηση αιτήσεων', Url::to(['enable/confirm-enable']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
                        </p>
                        <p>Η υποβολή των αιτήσεων είναι
                            <strong>
                                <span class="text-danger">απενεργοποιημένη.</span>
                            </strong>
                        </p>
                        <?php endif; ?>
                        <hr/>

                        <p>
                            <?= Html::a(Html::icon('trash') . ' Εκκαθάριση στοιχείων', Url::to(['admin/clear-data']), ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Η ενέργεια αυτή είναι μη αναστρέψιμη! Είστε βέβαιοι;']) ?>
                        </p>
                        <p>Η επιλογή αυτή θα διαγράψει όλα τα δεδομένα που αφορούν
                            <strong>αιτήσεις</strong>,
                            <strong>αιτούντες</strong>,
                            <strong>κενά</strong>,
                            <strong>περιφερειακές ενότητες</strong> και
                            <strong>στοιχεία καταγραφής ενεργειών χρηστών</strong>.</p>
                        <p class="text-danger">Προσοχή! Η ενέργεια αυτή είναι
                            <strong>μή αναστρέψιμη</strong>!</p>
                        <hr/>

                        <p>
                            <?= Html::a(Html::icon('list') . ' Αρχείο καταγραφής', Url::to(['audit-log/index']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Στοιχεία καταγραφής ενεργειών χρηστών.</p>
                        <p class="text-info">Προβάλλονται στοιχεία καταγραφής που έχουν αποθηκευτεί στη βάση δεδομένων. Περισσότερες λεπτομέρειες
                            και καταγραφές γίνονται στα αρχεία καταγραφής.</p>
                        <hr/>

                        <?php endif; ?>

                        <p>
                            <?= Html::a(Html::icon('file') . ' Προβολή', Url::to(['page/index']), ['class' => 'btn btn-primary']) ?>
                        </p>
                        <p>Κείμενα που χρησιμοποιούνται στην εφαρμογή.</p>

                    </div>
                </div>
            </div>

        </div>
    </div>