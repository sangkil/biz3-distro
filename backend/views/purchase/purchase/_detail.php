<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\purchase\Purchase;
use backend\models\purchase\PurchaseDtl;

/* @var $this View */
/* @var $model Purchase */
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 10%">#</th>
            <th>
                Product Name
            </th>
            <th class="items" style="width: 25%">
                Qty
            </th>
            <th class="items" style="width: 25%">
                Price
            </th>
            <th style="width: 20%">
                Uom
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
        'model' => PurchaseDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
