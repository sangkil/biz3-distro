<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Sales */

$this->title = 'Update Sales: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Saless', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
