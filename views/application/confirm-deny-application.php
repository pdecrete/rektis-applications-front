<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση δήλωσης άρνησης αίτησης';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jumbotron">
    <h1>Επιβεβαίωση άρνησης αίτησης</h1>
    <p>Είστε βέβαιοι ότι επιθυμείτε να δηλώσετε άρνηση υποβολής αίτησης;</p>
    <p><?= Html::a('Eίμαι βέβαιος', ['deny'], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Η δήλωση άρνησης είναι μη αναστρέψιμη ενέργεια. Είστε απόλυτα βέβαιοι;']) ?></p>
    <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
</div>
