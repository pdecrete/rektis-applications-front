<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Η αίτησή μου';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="application-view">

    <div class="btn-toolbar">
        <?php if ($enable_applications === true) : ?>
            <?= Html::a('Διαγραφή', ['delete-my-application'], ['class' => 'pull-right btn btn-danger']); ?>
        <?php endif; ?>
        <?= Html::a('Εκτύπωση', ['my-application', 'printMode' => '1'], ['class' => 'pull-right btn btn-primary']) ?>
    </div>


    <h1><?= Html::encode($this->title) ?></h1>
    <div class="well well-sm">
        <div class="row item">
            <div class="col-sm-4">
                <h2><?= $user->firstname ?> <small>(Όνομα)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->lastname ?> <small>(Επώνυμο)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->email ?> <small>(Email)</small></h2>
            </div>
        </div>
        <div class="row item">
            <div class="col-sm-4">
                <h2><?= $user->specialty ?> <small>(Ειδικότητα)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->vat ?> <small>(Α.Φ.Μ.)</small></h2>
            </div>
            <div class="col-sm-4">
                <h2><?= $user->identity ?> <small>(Ταυτότητα)</small></h2>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        Ημερομηνία υποβολής: 
        <?php if ($last_submit_model !== null) : ?>
            <strong><?= $last_submit_model->log_time_str; ?></strong>
        <?php else: ?>
            <span class="label label-danger">Δεν εντοπίστηκε</span>
        <?php endif; ?>
    </div>

    <table class="table table-bordered table-hover table-striped">
        <thead>
        <th>Σειρά προτίμησης</th>
        <th>Κενό προτίμησης</th>
        <th>Περιφερειακή ενότητα</th>
        <th>Περιφέρεια</th>
        </thead>
        <tbody>
            <?=
            ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_myapplication_item',
                'summary' => '<h3>{totalCount} προτιμήσεις</h3>'
            ]);

            ?>
        </tbody>
    </table>
    <div style="display: block; margin: auto; width:50%;">
        <?php
        $actionlogo = realpath(dirname(__FILE__) . '/../../web/images/logo.jpg');
        echo Html::img('images/logo.jpg')

        ?>
    </div>
</div>
