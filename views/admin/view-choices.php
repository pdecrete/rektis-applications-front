<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Prefecture;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Choice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Κενά';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="choices-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'prefecture_id',
            'value' => 'prefecture.prefecture',
            'filter' => ArrayHelper::map(Prefecture::find()->asArray()->all(), 'id', 'prefecture'),
        ],
        'position',
        'specialty',
    ],
]);

?>
</div>
