<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Uom */

$this->title = 'Update Satuan: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Uoms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row uom-update">
    <div class="col-lg-5">
      <?= $this->render('_form', [
        'model' => $model,
    ]) ?>  
    </div>
</div>
