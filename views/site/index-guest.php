<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
        <p>Συνδεθείτε για να υποβάλλετε αίτηση ή για να προβάλλετε την ήδη υποβληθείσα αίτηση σας.</p>
        <p><?= Html::a('Σύνδεση', ['login'], ['class' => 'btn btn-primary']) ?></p>
    </div>

</div>
