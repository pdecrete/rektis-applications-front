<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use app\models\Applicant;

/* @var $this yii\web\View */

$this->title = 'Αιτήσεις';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Διαχείριση αιτήσεων</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <?php //TODO: CHECK THE CONDITION OF if
                 if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()) : ?>
                <?php $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
                      if ($user->applications): ?>
						  <div class="col-lg-4">
							  <h2>Επεξεργασία</h2>
							  <p>Επεξεργαστείτε την αίτηση που έχετε υποβάλλει</p>
							  <?php if ($enable_applications === true) : ?>
									   <p><?= Html::a('Επεξεργασία', Url::to(['application/apply']), ['class' => 'btn btn-success']) ?></p>
							  <?php else: ?>
									   <p><?= Html::a('Επεξεργασία', '#', ['class' => 'btn btn-success disabled', 'disabled' => 'disabled']) ?></p>
									   <p class="text-danger">Η υποβολή αιτήσεων δεν είναι διαθέσιμη.</p>
							  <?php endif; ?>
						  </div>
				<?php else: ?>
						  <div class="col-lg-4">
							  <h2>Καταχώρηση</h2>
							  <p>Υποβάλλετε την αίτησή σας εδώ.</p>
							  <?php if ($enable_applications === true) : ?>
									   <p><?= Html::a('Υποβολή', Url::to(['application/apply']), ['class' => 'btn btn-success']) ?></p>
							  <?php else: ?>
									   <p><?= Html::a('Υποβολή', '#', ['class' => 'btn btn-success disabled', 'disabled' => 'disabled']) ?></p>
									   <p class="text-danger">Η υποβολή αιτήσεων δεν είναι διαθέσιμη.</p>
							  <?php endif; ?>
						  </div>
				<?php endif; ?>
				<div class="col-lg-4">
					<h2>Προβολή</h2>
					<p>Προβάλλετε την αίτηση σας εδώ.</p>
					<p><?= Html::a('Προβολή', Url::to(['application/my-application']), ['class' => 'btn btn-primary']) ?></p>
				</div>
				<?php if (count($user->applications) === 0): ?>
						 <div class="col-lg-4">
							 <h2>Άρνηση Δήλωσης</h2>
							 <p>Δηλώστε άρνηση υποβολής αίτησης εδώ.</p>
							 <p><?= Html::a('Άρνηση', Url::to(['application/request-deny']), ['class' => 'btn btn-primary']) ?></p>
						 </div>
					  <?php endif; ?>
			<?php endif; ?>
        </div>

    </div>
</div>
