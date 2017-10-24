<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuditLog */

$this->title = 'Ενημέρωση καταγραφής: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Αρχείο καταγραφής ενεργειών χρηστών', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Ενημέρωση καταγραφής';

?>
<div class="audit-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
