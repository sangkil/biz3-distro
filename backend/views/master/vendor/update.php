<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Vendor */

$this->title = 'Update Vendor: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
