<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\inventory\Transfer;
use backend\models\inventory\TransferDtl;

/* @var $this View */
/* @var $model Transfer */
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th style="width: 10%">#</th>
            <th>
                Product Name
            </th>
            <th class="items" style="width: 15%">
                Qty
            </th>
            <th style="width: 10%">
                Uom
            </th>
        </tr>
        <tr>
            <td colspan="2">                
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input id="input-product" class="form-control" placeholder="Search Product..">
                </div>
            </td>
        </tr>
    </thead>
    <tbody>
        <?=
        TabularInput::widget([
            'id' => 'detail-grid',
            'allModels' => $model->items,
            'modelClass' => TransferDtl::className(),
            'options' => ['tag' => 'tbody'],
            'itemOptions' => ['tag' => 'tr'],
            'itemView' => '_item_detail',
            'clientOptions' => [
            ]
        ])
        ?>
    </tbody>
</table>