<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Warehouse */

$this->title = 'Update U2 Warehouse: ' . ' ' . $model->warehouse_id;
$this->params['breadcrumbs'][] = ['label' => 'U2 Warehouses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->warehouse_id, 'url' => ['view', 'warehouse_id' => $model->warehouse_id, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row u2-warehouse-update">
    <div class="col-lg-5">
      <?= $this->render('_form', [
        'model' => $model,
    ]) ?>  
    </div>  
</div>
