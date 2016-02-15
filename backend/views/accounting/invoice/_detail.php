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
            <th style="width: 10%">#</th>
            <th>
                Item Name
            </th>
            <th class="items" style="width: 25%">
                Qty
            </th>
            <th style="width: 25%">
                Value
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
