<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\log\Logger;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuditLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Αρχείο καταγραφής ενεργειών χρηστών';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="audit-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'level',
                'value' => 'level_label',
                'filter' => Html::activeDropDownList($searchModel, 'level', array_combine(
                        [Logger::LEVEL_ERROR, Logger::LEVEL_WARNING, Logger::LEVEL_INFO, Logger::LEVEL_TRACE], ['Error', 'Warning', 'Info', 'Trace']
                    ), [
                    'class' => 'form-control', 'prompt' => 'Όλα'
                ]),
                'format' => 'html'
            ],
            'category',
            [
                'attribute' => 'log_time',
                'value' => 'log_time_str',
                'filter' => false
            ],
            'prefix:ntext',
            'message:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]);

    ?>
</div>
