<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\inventory\GoodsMovement;
use backend\models\inventory\GoodsMovementDtl;

/* @var $this View */
/* @var $model GoodsMovement */

?>
<a id="add-row" title="Add" href="#"><span class="glyphicon glyphicon-plus"></span></a>
<div class="col-lg-12">
    <div class="panel panel-info">        
        <table id="detail-grid" class="table table-striped">            
            <?=
            TabularInput::widget([
                'id' => 'detail-grid',
                'allModels' => $model->items,
                'modelClass' => GoodsMovementDtl::className(),
                'options' => ['tag' => 'tbody'],
                'itemOptions' => ['tag' => 'tr'],
                'itemView' => '_item_detail',
                'clientOptions' => [
                    'btnAddSelector' =>'#add-row'
                ]
            ])
            ?>
        </table>
    </div>
</div>
