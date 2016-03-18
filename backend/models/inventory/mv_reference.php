<?php
use backend\models\inventory\GoodsMovement;
return [
    // purchase
    10 => [
        'name' => 'Purchase',
        'class' => 'backend\models\purchase\Purchase',
        'action' => '/purchase/purchase/view',
        'type' => GoodsMovement::TYPE_RECEIVE,
        'onlyStatus' => 20,
        'vendor'=>'vendor_id',
        'items' => 'generateReceive',
    ],
    // transfer
    30 => [
        'name' => 'Transfer',
        'class' => 'backend\models\inventory\Transfer',
        'action' => '/inventory/transfer/view',
        'type' => GoodsMovement::TYPE_ISSUE,
        'onlyStatus' => 20,
        'items' => 'generateReceive',
    ],
    // sales
    60 => [
        'name' => 'Sales',
        'class' => 'backend\models\sales\Sales',
        'action' => '/sales/sales/view',
        'type' => 20,
        'onlyStatus' => 20,
        'items' => 'items',
        'itemField' => [
            'product_id' => 'product_id',
            'uom_id' => 'uom_id',
            'value' => 'price',
            'cogs' => 'cogs'
        ],
    ],
];
