<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $models app\models\Application[] */
/* @var $form yii\widgets\ActiveForm */

$model0 = $models[0];

?>

<div class="application-form">

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

    <?php $form = ActiveForm::begin(['id' => 'application-dynamic-form', 'layout' => 'horizontal']); ?>

    <?php
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 10,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $model0,
        'formId' => 'application-dynamic-form',
        'formFields' => [
            'choice_id',
            'order',
        ],
    ]);

    ?>
    <div class="container-items">
        <div class="row text-right">
            <button type="button" class="add-item btn btn-success btn-sm"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Προσθήκη επιλογής</button>
            <p>&nbsp;</p> 
        </div>
        <?php foreach ($models as $index => $model): ?>
            <div class="row item">
                <div class="col-sm-7">
                    <?= $form->field($model, "[{$index}]choice_id")->textInput() ?>
                    <?php
                    if (!$model->isNewRecord) {
//                        echo Html::activeHiddenInput($model, "[{$index}]id");
                        echo $form->field($model, "[{$index}]id")->hiddenInput()->label(false);
                    }
                    echo $form->field($model, "[{$index}]applicant_id")->hiddenInput()->label(false)->hint(false);

                    ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($model, "[{$index}]order")->textInput() ?>
                </div>
                <div class="col-sm-1">
                    <button type="button" class="pull-right remove-item btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></button>
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
