<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
    </div>

    <div class="body-content">

        <div class="row">
	    <?php if(Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin())) : ?>
            <div class="col-lg-4">
                <h2>Καταχώρηση</h2>
                <p>Υποβάλλετε την αίτησή σας εδώ.</p>
                <p><?= Html::a('Υποβολή', Url::to(['application/apply']), ['class' => 'btn btn-success']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Προβολή</h2>
                <p>Προβάλλετε την αίτηση σας εδώ.</p>
                <p><?= Html::a('Προβολή', Url::to(['application/my-application']), ['class' => 'btn btn-primary']) ?></p>
            </div>
            <?php endif; ?>
            <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()) : ?>
			<div class="col-lg-4">
                <h2>Ενεργοποίηση αιτήσεων</h2>
                <?php if(1 == \Yii::$app->db->createCommand('SELECT enable_applications FROM config WHERE id=1')->queryColumn()[0]):?>
					<p>Η υποβολή των αιτήσεων είναι <strong><span class="text-success">ενεργοποιημένη.</span></strong>.</p>
					<p><?= Html::a('Απενεργοποίηση', Url::to(['enable-applications/confirm-enable']), ['class' => 'btn btn-info', 'data-method' => 'POST']) ?></p>
                <?php else: ?>
					<p>Η υποβολή των αιτήσεων είναι <strong><span class="text-danger">απενεργοποιημένη.</span></strong></p>
					<p><?= Html::a('Ενεργοποίηση', Url::to(['enable-applications/confirm-enable']), ['class' => 'btn btn-info', 'data-method' => 'POST']) ?></p>
				<?php endif; ?>
            </div>
            <div class="col-lg-4">
                <h2>Εξαγωγή</h2>
                <p>Η εξαγωγή είναι διαθέσιμη μόνο στους διαχειριστές.</p>
                <p><?= Html::a('Εξαγωγή', Url::to(['admin/export']), ['class' => 'btn btn-info', 'data-method' => 'POST']) ?></p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
