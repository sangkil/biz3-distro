<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Invoice;
use backend\models\accounting\InvoiceDtl;

/* @var $this View */
/* @var $model Invoice */
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th>
                Item Name
            </th>
            <th class="items" style="text-align:right;width: 10%">
                Qty
            </th>
            <th style="text-align:right;width: 15%">
                Unit Cost
            </th>
            <th style="text-align:right; width: 15%">
                Line Total
            </th>
        </tr>
        <tr>
            <td colspan="5">
                <div class="input-group" style="width:100%;">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input id="input-product" class="form-control" placeholder="Search Product..">
                </div>
            </td>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'modelClass' => InvoiceDtl::className(),
        'options' => ['tag' => 'tbody'],
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
