<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Επισκόπηση στοιχείων';
$this->params['breadcrumbs'][] = ['label' => 'Διαχειριστικές λειτουργίες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="application-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row item">
        <div class="col-sm-4">
            <h2>Ενεργές αιτήσεις <span class="label label-primary"><?= $applications ?></span></h2>
        </div>
        <div class="col-sm-4">
            <h2>Σύνολο εγγεγραμμένων <span class="label label-info"><?= $applicants ?></span></h2>
        </div>
        <div class="col-sm-4">
            <h2>Εγγραφές κενών <span class="label label-info"><?= $choices ?></span></h2>
        </div>
    </div>

    <h2>Πλήθος αιτήσεων ανά πλήθος προτιμήσεων</h2>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'showFooter' => true,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'footer' => 'Σύνολο αιτήσεων'
            ],
            [
                'label' => 'Πλήθος αιτήσεων',
                'attribute' => 'applicants_count',
                'footer' => $applications
            ],
            [
                'label' => 'Πλήθος προτιμήσεων',
                'attribute' => 'items_count',
                'footer' => '-'
            ],
        ],
    ]);

    ?>
</div>
