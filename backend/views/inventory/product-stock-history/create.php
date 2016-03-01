<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovementDtl */

$this->title = 'Create Goods Movement Dtl';
$this->params['breadcrumbs'][] = ['label' => 'Goods Movement Dtls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-dtl-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
