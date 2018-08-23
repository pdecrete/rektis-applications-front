<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Choice;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $models app\models\Application[] */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="application-form-container">
    <?= $this->render('_applicant_info_header', ['user' => $user]) ?>

    <?= $information ?>

    <?php $form = ActiveForm::begin(['id' => 'application-form', 'layout' => 'horizontal']); ?>

    <div class="container-items">
        <div class="panel-group">
            <?php foreach ($models as $prefect_name => $st_choices): ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <?php echo 'ΝΟΜΟΣ ', $prefect_name ?>
                </div>
                <div class="panel-body">
                    <?php foreach ($st_choices as $idx_school_type => $choices): ?>
                        <?php if ($idx_school_type > 0): ?>
                        <div class="well well-sm" role="alert">
                            <?php echo Choice::schooltypeLabel($idx_school_type); ?>
                        </div>
                        <?php endif; ?>
                        <?php
                        $url = Url::to([
                            'choice/select-choices', 
                            'prefecture' => $prefectures_choices[$prefect_name],
                            'specialty' => $user->specialty,
                            'school_type' => $idx_school_type
                        ]);

                        $counter = 1;
                        foreach ($choices as $index => $choice) {
                            $selected_position = empty($choice->choice) ? '' : $choice->choice->position;
                            echo $form->field($choice, "[{$index}]choice_id")->label('Επιλογή ' . $counter++ . 'ου κενού')->widget(Select2::classname(), [
                                'initValueText' => $selected_position,
                                'options' => [
                                    'placeholder' => 'Επιλέξτε...'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 0,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Αναμονή για αποτελέσματα...'; }"),
                                        'searching' => new JsExpression("function () { return 'Αναζήτηση...'; }"),
                                        'noResults' => new JsExpression("function () { return 'Κανένα αποτέλεσμα.'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $url,
                                        'method' => 'POST',
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return $("#application-form").serialize() + "&term=" + (params.term || ""); }'), // TODO make this a reusable function
                                        'delay' => 500,
                                        'cache' => true
                                    ],
                                    'templateResult' => new JsExpression('function (res) { return res.text; }'),
                                    'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                                ],
                            ])->hint('');

                            if (!$choice->isNewRecord) {
                                echo $form->field($choice, "[{$index}]id")->hiddenInput()->label(false);
                            }
                        }
                        ?>
                    <?php endforeach; ?>
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