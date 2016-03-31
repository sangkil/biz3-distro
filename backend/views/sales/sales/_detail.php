<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\sales\Sales;
use backend\models\sales\SalesDtl;

/* @var $this View */
/* @var $model Sales */
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 10%">#</th>
            <th>
                Product Name
            </th>
            <th class="items" style="width: 10%">
                Price
            </th>
            <th class="items" style="width: 10%">
                Qty
            </th>
            <th style="width: 10%">
                Uom
            </th>
            <th style="width: 10%">
                Disc %
            </th>
            <th style="width: 10%">
                Line Total
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <div class="input-group" style="width:100%;">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input id="input-product" class="form-control" placeholder="Search Product..">
                </div>
            </td>
            <td colspan="3"></td>            
            <td>
<!--                <input id="global-discount" class="form-control" placeholder="Global Discount">-->
            </td>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'model' => SalesDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
