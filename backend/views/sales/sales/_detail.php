<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\sales\Sales;
use backend\models\sales\SalesDtl;

/* @var $this View */
/* @var $model Sales */
?>

<div class="col-lg-12">
    <input id="input-product">
</div>
<div class="col-lg-12">
    <div class="panel panel-info">
        <table class="table table-striped">
            <?=
            TabularInput::widget([
                'id' => 'detail-grid',
                'allModels' => $model->items,
                'modelClass' => SalesDtl::className(),
                'options' => ['tag' => 'tbody'],
                'itemOptions' => ['tag' => 'tr'],
                'itemView' => '_item_detail',
                'clientOptions' => [
                ]
            ])
            ?>
        </table>
    </div>
</div>
