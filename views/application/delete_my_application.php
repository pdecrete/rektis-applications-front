<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση διαγραφής αίτησης';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jumbotron">
    <h1>Επιβεβαίωση διαγραφής αίτησης</h1>
    <p>Είστε βέβαιοι για τη διαγραφή της αίτησης;</p>
    <p><?= Html::a('Διαγραφή', ['my-delete'], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Η διαγραφή είναι μη αναστρέψιμη ενέργεια. Είστε απόλυτα βέβαιοι;']) ?></p>
    <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
</div>
