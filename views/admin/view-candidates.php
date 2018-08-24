<?php

use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = 'Προβολή υποψηφίων εκπαιδευτικών';
$this->params['breadcrumbs'][] = ['label' => 'Διαχειριστικές λειτουργίες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="text-right"><span class="bg-danger">Χρωματική ένδειξη</span> για ένδειξη υποβολής άρνησης δήλωσης</p>
<?php
echo GridView::widget([
    'dataProvider' => $users,
    'rowOptions' => function ($m) {
        if ($m->state == 1) {
            return [ 'class' => 'danger' ];
        }
    },
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
            'format' => 'html',
            'value' => function ($m) { 
                return (count($m->applications) > 0) 
                    ? $m->last_submit_str 
                    : '<span class="text-danger">' . $m->last_submit_str . '</span>' . ($m->has_submitted ? '<br><span class="label label-danger">Έχει ακυρωθεί</span>' : ''); 
            },
        ],
        [
            'attribute' => 'state_ts_str',
            'label' => 'Άρνηση',
            'format' => 'html'
        ]
    ]
]);
