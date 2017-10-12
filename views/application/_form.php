<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Application;

/* @var $this yii\web\View */
/* @var $models app\models\Application[] */
/* @var $form yii\widgets\ActiveForm */

//$model0 = reset($models);

$notification_js = '$(".dynamicform_wrapper").on("limitReached", function (e) {
    alert("Έχετε φτάσει στο μέγιστο ότιο διαθέσιμων επιλογών");
    return true;
});
';
$this->registerJs($notification_js);
?>

<div class="application-form-container">

    <div class="row item">
        <div class="col-sm-4">
            <h2><?= $user->specialty ?> <small>Ειδικότητα</small></h2>
        </div>
        <div class="col-sm-4">
            <h2><?= $user->vat ?> <small>Α.Φ.Μ.</small></h2>
        </div>
        <div class="col-sm-4">
            <h2><?= $user->identity ?> <small>Ταυτότητα</small></h2>
        </div>
    </div>

    <div class="well">
        <h4>Οδηγίες συμπλήρωσης αίτησης</h4>
        <ul>
            <li>Μπορείτε να προσθέσετε <strong>από 1 έως <?= Yii::$app->params['max-application-items'] ?></strong> επιλογές</li>
            <li>Όταν ολοκληρώσετε τις επιλογές σας, πατήστε το κουμπί <strong>Αποθήκευση</strong></li>
        </ul>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'application-form', 'layout' => 'horizontal']); ?>
	
	<div class="container-items">
		<div class="panel-group">
			<?php 
			foreach($models as $prefect_name => $choices): ?>
			<div class="panel panel-info">
				<div class="panel-heading"><?php echo 'ΝΟΜΟΣ ' . $prefect_name ?></div>
				<div class="panel-body">
					<?php
					$options = ArrayHelper::map($prefectrs_choices_model::getChoices($prefectures_choices[$prefect_name], $user->specialty), 'id', 'position');
					$counter = 1;
					foreach($choices as $index => $choice):	   
					   echo $form->field($choice, "[{$index}]choice_id")->dropdownList($options, ['prompt' => 'Επιλέξτε...'])->label('Επιλογή ' . $counter++ . 'ου κενού');
					   if (!$choice->isNewRecord) {
                          echo $form->field($choice, "[{$index}]id")->hiddenInput()->label(false);
                    }
					endforeach;
					?>
					    
                </div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton('Αποθήκευση', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
