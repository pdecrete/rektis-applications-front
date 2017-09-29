<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $models app\models\Application[] */
/* @var $form yii\widgets\ActiveForm */

$model0 = reset($models);

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
            <li>Για να προσθέσετε νέα επιλογή, πατήστε το κουμπί <strong>Προσθήκη Επιλογής</strong></li>
            <li>Για να αφαιρέσετε μια επιλογή, πατήστε το κόκκινο κουμπί στα δεξιά της επιλογής</li>
            <li>Σε κάθε επιλογή πρέπει να συμπληρώσετε τη σειρά προτίμησής</li>
            <li>Κάθε επιλογή πρέπει να έχει διαφορετική σειρά προτίμησης</li>
            <li>Όταν ολοκληρώσετε τις επιλογές σας, πατήστε το κουμπί <strong>Αποθήκευση</strong></li>
        </ul>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'application-form', 'layout' => 'horizontal']); ?>

    <?php
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => \Yii::$app->params['max-application-items'],
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $model0,
        'formId' => 'application-form',
        'formFields' => [
            'choice_id',
            'order',
        ],
    ]);

    ?>
    <div class="container-items">
        <div class="row text-right">
            <button type="button" class="add-item btn btn-success btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Προσθήκη επιλογής</button>
            <p>&nbsp;</p> 
        </div>
        <?php foreach ($models as $index => $model): ?>
            <div class="row item">
                <div class="col-sm-6">
                    <?= $form->field($model, "[{$index}]choice_id")->dropdownList(ArrayHelper::map($user->choices, 'id', 'position'), ['prompt' => 'Επιλέξτε...'])->label('Επιλογή κενού') ?>
                    <?= ''; // $form->field($model, "[{$index}]choice_id")->textInput() ?>
                    <?php
                    if (!$model->isNewRecord) {
                        echo $form->field($model, "[{$index}]id")->hiddenInput()->label(false);
                    }

                    ?>
                </div>
                <div class="col-sm-5">
                    <?=
                    $form->field($model, "[{$index}]order")->textInput()->label('Σειρά επιλογής', ['class' => 'col-sm-4'])

                    ?>
                </div>
                <div class="col-sm-1">
                    <button type="button" class="pull-right remove-item btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Αποθήκευση' : 'Ενημέρωση', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
