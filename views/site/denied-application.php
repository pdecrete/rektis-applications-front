<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
		<p>Έχετε δηλώσει <strong>άρνηση</strong> δήλωσης τοποθέτησης</p>
		<p><?= Html::a('Εκτύπωση δήλωσης άρνησης', ['application/print-denial'], ['class' => 'btn btn-primary', 'data-method' => 'POST']); ?></p>
	</div>
</div>
