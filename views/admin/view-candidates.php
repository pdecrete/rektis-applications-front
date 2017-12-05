<?php

use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = 'Προβολή υποψηφίων εκπαιδευτικών';
$this->params['breadcrumbs'][] = ['label' => 'Διαχειριστικές λειτουργίες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $users,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Επώνυμο',
            'attribute' => 'lastname'
        ],
        [
            'label' => 'Όνομα',
            'attribute' => 'firstname'
        ],
        [
            'label' => 'Πατρώνυμο',
            'attribute' => 'fathername'
        ],
        [
            'label' => 'Α.Φ.Μ.',
            'attribute' => 'vat'
        ],
        [
            'label' => 'Α.Δ.Τ.',
            'attribute' => 'identity'
        ],
        [
            'label' => 'Ειδικότητα',
            'attribute' => 'specialty'
        ],
        [
            'label' => 'Μόρια',
            'attribute' => 'points'
        ],
        [
            'attribute' => 'last_submit_str',
            'format' => 'html'
        ]
    ]
]);
