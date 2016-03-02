<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = 'Create Goods ' . ($model->nmType ? ucfirst(strtolower($model->nmType)) : 'Movement');
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
