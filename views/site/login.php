<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Choice;
use himiklab\yii2\recaptcha\ReCaptcha;

$this->title = 'Σύνδεση';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login">
    <h1><?= $this->title ?></h1>

    <p>Παρακαλώ συμπληρώστε τα στοιχεία σας για να συνδεθείτε στην εφαρμογή. </p>

    <?php
    $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
    ]);

    ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?php
    $temp_array = Choice::find()->select('specialty')->distinct()->orderBy('specialty')->indexBy('specialty')->asArray()->all();
    $specialties = ["-" => "-"];
    foreach ($temp_array as $key => $value) {
        $specialties[$key] = $key;
    }
    echo $form->field($model, 'specialty')->dropDownList($specialties);

    ?>

    <?php if (\Yii::$app->params['allow-recaptcha']) : ?>
    <?=
    $form->field($model, 'captchavalidation', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-11\">{input} {label}</div>\n<div class=\"col-lg-7 col-lg-offset-1\">{error}</div>",
    ])->widget(
        \himiklab\yii2\recaptcha\ReCaptcha::className()
    )->label(false)

    ?>
    <?php endif; ?>

    <?=
    $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])

    ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Σύνδεση', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
