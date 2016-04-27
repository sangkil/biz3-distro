<?php

use yii\helpers\Html;
use backend\models\inventory\GoodsMovement;
/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = ($model->type == GoodsMovement::TYPE_RECEIVE) ? 'Penerimaan' : 'Mutasi';
$this->title = ($model->type == GoodsMovement::TYPE_ISSUE) ? 'Pengeluaran' : $this->title;
$this->title .= ' Barang';
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index','type'=>$model->type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
