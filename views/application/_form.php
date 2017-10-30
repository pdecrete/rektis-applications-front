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
    <?= $this->render('_applicant_info_header', ['user' => $user]) ?>

    <?= $this->render('_info_apply_usage') ?>

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
        <?= Html::submitButton('Υποβολή αίτησης', ['class' => 'btn btn-success btn-lg pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
