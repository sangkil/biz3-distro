<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Payment;
use backend\models\accounting\PaymentDtl;

/* @var $this View */
/* @var $model Payment */
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th style="width: 10%">
                Number
            </th>
            <th>
                Vendor
            </th>
            <th class="items" style="width: 10%">
                Date
            </th>
            <th class="items" style="width: 10%">
                Time to Due
            </th>
            <th style="width: 10%;">
                Invoice Value
            </th>
            <th style="width: 10%;">
                Payment Value
            </th>
            <th class="items" style="width: 20%">
                Sisa
            </th>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'model' => PaymentDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail_view',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
