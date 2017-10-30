<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Σχετικά';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<p><?= Yii::powered() ?></p>
