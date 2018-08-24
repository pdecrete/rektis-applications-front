<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
		<p>Έχετε δηλώσει <strong>άρνηση</strong> δήλωσης τοποθέτησης</p>
		<p><?= Html::a('<span class="glyphicon glyphicon-save-file"></span> Εκτύπωση δήλωσης άρνησης', ['application/print-denial'], ['class' => 'btn btn-primary', 'data-method' => 'POST']); ?></p>
	</div>
	<p class="text-center"><?= Html::a('<span class="glyphicon glyphicon-trash"></span> Ακύρωση δήλωσης άρνησης', ['application/recall-deny'], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Είστε απολύτως βέβαιοι;', 'data-method' => 'POST']); ?></p>
</div>
