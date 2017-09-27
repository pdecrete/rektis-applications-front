<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Testing'; // $model->id
$this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="application-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update'], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete'], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])

        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'applicant_id',
            'choice_id',
            'order',
            'updated',
            // 'deleted',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>


</div>
