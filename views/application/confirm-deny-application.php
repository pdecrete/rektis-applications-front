<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση υποβολής αρνητικής δήλωσης';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1>Υποβολή αρνητικής δήλωσης</h1>
<div class="text-left">
    <?= $this->render('_applicant_info_header', ['user' => $user]) ?>
</div>
<div class="well well-lg">
    <?= $content ?>
</div>
<div class="jumbotron">
    <p>Πατήστε το κουμπί αποδέχομαι εφόσον μελετήσατε και συμφωνείτε με τα παραπάνω.<p>
    <p><?= Html::a('Eίμαι βέβαιος / βέβαιη', ['deny'], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Η δήλωση άρνησης υποβολής αίτησης είναι μη αναστρέψιμη ενέργεια. Είστε απόλυτα βέβαιοι;']) ?></p>
    <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
</div>
