<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Invoice;
use backend\models\accounting\InvoiceDtl;

/* @var $this View */
/* @var $model Invoice */
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
                'modelClass' => InvoiceDtl::className(),
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
