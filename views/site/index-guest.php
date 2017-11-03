<?php

use yii\bootstrap\Html;

/**
 * @var $this yii\web\View 
 * @var $period_open boolean True if applications are open
 */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
        <p>Συνδεθείτε για να υποβάλλετε αίτηση ή για να προβάλλετε την ήδη υποβληθείσα αίτηση σας.</p>
        <?php if ($period_open === false) :?>
        <p class="text-danger">Δεν είναι ενεργή η περίοδος υποβολής δηλώσεων.</p>
        <?php endif; ?>
        <p><?= Html::a('Σύνδεση', ['login'], ['class' => 'btn btn-primary']) ?></p>
    </div>

</div>
