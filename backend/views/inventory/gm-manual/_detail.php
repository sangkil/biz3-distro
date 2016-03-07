<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\inventory\GoodsMovement;
use backend\models\inventory\GoodsMovementDtl;
use yii\helpers\Html;

/* @var $this View */
/* @var $model GoodsMovement */
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th>
                Product Name
            </th>
            <th class="items" style="width: 20%">
                Cost
            </th>
            <th class="items" style="width: 15%">
                Qty
            </th>
            <th style="width: 15%">
                Uom
            </th>
            <th style="width: 15%">
                Total Line
            </th>
        </tr>
        <tr>
            <td colspan="3">
                <div class="input-group" style="width:100%;">
<!--                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>-->
                    <input id="input-product" class="form-control" placeholder='Search Product..'>
                    <span class="input-group-btn">
                        <?=
                        Html::button('<i class="fa fa-download"></i>&nbsp;PO-Item', ['class' => 'btn btn-warning',
                            'data-toggle' => "modal",
                            'data-target' => "#listPO",
//                            'data-title' => "Detail Data"
                            ]);
                        ?> 
                    </span>
                </div>
            </td>
        </tr>
    </thead>
    <tbody>
        <?=
        TabularInput::widget([
            'id' => 'detail-grid',
            'allModels' => $model->items,
            'modelClass' => GoodsMovementDtl::className(),
            'options' => ['tag' => 'tbody'],
            'itemOptions' => ['tag' => 'tr'],
            'itemView' => '_item_detail',
            'clientOptions' => [
            ]
        ])
        ?>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="listPO" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;&nbsp;Close</button>
      </div>
    </div>
  </div>
</div>