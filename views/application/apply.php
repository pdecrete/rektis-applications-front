<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Νέα αίτηση';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="application-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
//        'model' => $model,
        'models' => $models,
        'user' => $user,
        'prefectures_choices' => $prefectures_choices,
        'prefectrs_choices_model' => $prefectrs_choices_model
    ])

    ?>

</div>
