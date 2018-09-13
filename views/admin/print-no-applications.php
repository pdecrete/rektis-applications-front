<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

$this->title = 'Εκπαιδευτικοί χωρίς αίτηση και χωρίς δήλωση άρνησης';
$this->params['breadcrumbs'][] = ['label' => 'Διαχειριστικές λειτουργίες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h3 style="text-align: center;"><?= Html::encode($this->title) ?></h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th style="width: 20%">Επώνυμο</th>
            <th style="width: 20%">Όνομα</th>
            <th style="width: 15%">Πατρώνυμο</th>
            <th style="width: 15%">Α.Φ.Μ.</th>
            <th style="width: 15%">Α.Δ.Τ.</th>
            <th style="width: 10%">Ειδικότητα</th>
        </tr>
    </thead>
    <tbody>
        <?=
        ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model, $key, $index, $widget) {
                $serial = $index + 1;
                return <<< EOUTMPL
        <tr>
            <td>{$serial}</td>
            <td>{$model->lastname}</td>
            <td>{$model->firstname}</td>
            <td>{$model->fathername}</td>
            <td>{$model->vat}</td>
            <td>{$model->identity}</td>
            <td>{$model->specialty}</td>
        </tr>
EOUTMPL;
            },
            'summary' => '<h3>Συνολικά: {totalCount}</h3>'
        ]);

        ?>
    </tbody>
</table>
<p class="text-muted"><small>Παραγωγή αρχείου <?php echo date('c'); ?></small></p>
