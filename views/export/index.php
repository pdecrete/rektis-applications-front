<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Εξαγωγή';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1>Εξαγωγή αιτήσεων</h1>

<p>Η λειτουργία αυτή εξάγει όλες τις μη διεγραμμένες αιτήσεις σε μορφή CSV.</p>
<p><?= Html::a(Html::icon('save') . ' Εξαγωγή σε CSV', Url::to(['export/csv']), ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?></p>
