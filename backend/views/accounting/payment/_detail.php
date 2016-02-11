<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Payment;
use backend\models\accounting\PaymentDtl;

/* @var $this View */
/* @var $model Payment */
?>

<div class="col-lg-12">
    <input id="input-invoice">
</div>
<div class="col-lg-12">
    <div class="panel panel-info">
        <table class="table table-striped">
            <?=
            TabularInput::widget([
                'id' => 'detail-grid',
                'allModels' => $model->items,
                'modelClass' => PaymentDtl::className(),
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
