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
            <th class="items" style="width: 10%">
                Cost/pcs
            </th>
            <th class="items" style="width: 15%">
                <?= ($model->type == $model::TYPE_ISSUE) ? 'Qty Remain' : 'Qty Issued' ?>
            </th>
            <th class="items" style="width: 10%">
                Qty Trans
            </th>
            <th style="width: 10%">
                Uom
            </th>
            <th style="width: 10%">
                Total Line
            </th>
        </tr>
    </thead>
    <tbody>
        <?=
        TabularInput::widget([
            'id' => 'detail-grid',
            'allModels' => $model->items,
            'viewParams' => ['is_issue' => ($model->type == $model::TYPE_ISSUE)],
            'model' => GoodsMovementDtl::className(),
            'tag' => 'tbody',
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