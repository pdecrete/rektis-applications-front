<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Choice;

/* @var $this yii\web\View */
/* @var $models app\models\Application[] */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="application-form-container">
    <div class="well well-sm">
        <div class="row item">
            <div class="col-sm-4">
                <h2><?= $user->firstname ?> <small>(Όνομα)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->lastname ?> <small>(Επώνυμο)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->email ?> <small>(Email)</small></h2>
            </div>
        </div>
        <div class="row item">
            <div class="col-sm-4">
                <h2><?= $user->specialty ?> <small>(Ειδικότητα)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->vat ?> <small>(Α.Φ.Μ.)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->identity ?> <small>(Ταυτότητα)</small></h2>
            </div>
        </div>
    </div>

    <div class="well">
        <h4>Οδηγίες συμπλήρωσης αίτησης</h4>
        <ul>
            <li>Οι νομοί παρουσιάζονται με τη σειρά προτίμησης που έχετε ήδη δηλώσει.</li>
            <li>Επιλέξτε <strong>όλα</strong> τα σχολεία για κάθε νομό με τη σειρά προτίμησης που επιθυμείτε.</li>
            <li>Όταν ολοκληρώσετε τις επιλογές σας, πατήστε το κουμπί <strong>"Αποθήκευση"</strong>.</li>
        </ul>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'application-form', 'layout' => 'horizontal']); ?>

    <div class="container-items">
        <div class="panel-group">
            <?php foreach ($models as $prefect_name => $choices): ?>
                <div class="panel panel-info">
                    <div class="panel-heading"><?php echo 'ΝΟΜΟΣ ', $prefect_name ?></div>
                    <div class="panel-body">
                        <?php
                        $options = ArrayHelper::map(Choice::getChoices($prefectures_choices[$prefect_name], $user->specialty), 'id', 'position');
                        $counter = 1;
                        foreach ($choices as $index => $choice) {
                            echo $form->field($choice, "[{$index}]choice_id")->dropdownList($options, ['prompt' => 'Επιλέξτε...'])->label('Επιλογή ' . $counter++ . 'ου κενού');
                            if (!$choice->isNewRecord) {
                                echo $form->field($choice, "[{$index}]id")->hiddenInput()->label(false);
                            }
                        }

                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Αποθήκευση', ['class' => 'btn btn-success btn-lg pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
