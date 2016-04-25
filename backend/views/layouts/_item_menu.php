<?php
$temp_roles = [];
$user_roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
foreach ($user_roles as $key => $value) {
    $temp_roles[] = $key;
}

return[
    '<li class="header">MAIN NAVIGATION</li>',
    ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site/index'], 'visible' => !Yii::$app->user->isGuest],
    ['label' => 'Data Master', 'icon' => 'th', 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'Orgn', 'icon' => 'check', 'url' => ['/master/orgn']],
            ['label' => 'Branch', 'icon' => 'check', 'url' => ['/master/branch']],
            ['label' => 'Warehouse', 'icon' => 'check', 'url' => ['/master/warehouse']],
            ['label' => 'Item Master', 'icon' => 'check',
                'items' => [
                    ['label' => 'Product', 'icon' => 'check', 'url' => ['/master/product']],
                    ['label' => 'Uom', 'icon' => 'check', 'url' => ['/master/uom']],
                    ['label' => 'Product Group', 'icon' => 'check', 'url' => ['/master/product-group']],
                    ['label' => 'Category', 'icon' => 'check', 'url' => ['/master/category']],
                ],
            ],
            ['label' => 'Vendors', 'icon' => 'check', 'url' => ['/master/vendor']],
        ],
    ],
    ['label' => 'Sales', 'icon' => 'shopping-cart', 'iconOptions' => ['class' => 'text-orange'], 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'Point of Sales', 'icon' => 'check', 'url' => ['/sales/sales/create']],
            ['label' => 'Sales Return', 'icon' => 'check',],
            ['label' => 'Price Mngnt', 'icon' => 'check',
                'items' => [
                    ['label' => 'Pricing Category', 'icon' => 'check', 'url' => ['/sales/price-category']],
                    ['label' => 'Sales Pricing', 'icon' => 'check', 'url' => ['/sales/price']],
                ],
            ],
            ['label' => 'Discount Mngnt', 'icon' => 'check',
                'items' => [
                    ['label' => 'Discount Type', 'icon' => 'check', 'url' => '#'],
                    ['label' => 'Sales Discount', 'icon' => 'check', 'url' => '#'],
                ],
            ],
        ],
    ],
    ['label' => 'Material Mangmnt', 'icon' => 'truck', 'iconOptions' => ['class' => 'text-success'], 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'Goods Receive', 'icon' => 'check', 'url' => ['/inventory/gm-manual', 'GoodsMovement[type]' => 10]],
            ['label' => 'Goods Issue', 'icon' => 'check', 'url' => ['/inventory/gm-manual', 'GoodsMovement[type]' => 20]],
            ['label' => 'Material Transfer', 'icon' => 'check', 'url' => ['/inventory/transfer']],
            ['label' => 'Stock Mangmnt', 'icon' => 'check',
                'items' => [
                    ['label' => 'Stock', 'icon' => 'check', 'url' => ['/inventory/product-stock']],
                    ['label' => 'Stock History', 'icon' => 'check', 'url' => ['/inventory/stock-history']],
                    ['label' => 'Stock Adjustment', 'icon' => 'check', 'url' => ''],
                ],
            ],
            ['label' => 'Opname', 'icon' => 'check', 'url' => ''],
        ],
    ],
    ['label' => 'FI & Accounting', 'icon' => 'buysellads', 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'COA', 'icon' => 'check', 'url' => ['/accounting/coa']],
            ['label' => 'FI Periode', 'icon' => 'check',
                'items' => [
                    ['label' => 'Periodes', 'icon' => 'check', 'url' => ['/accounting/acc-periode']],
                    ['label' => 'Closing', 'icon' => 'check', 'url' => ['/accounting/acc-periode/close']],
                ],
            ],
            ['label' => 'General Journals', 'icon' => 'check', 'url' => ['/accounting/general-ledger'],
                'items' => [
                    ['label' => 'Journal', 'icon' => 'check', 'url' => ['/accounting/general-ledger/index']],
                    ['label' => 'Journal Template', 'icon' => 'check', 'url' => ['/accounting/journal-template']],
                ],
            ],
            ['label' => 'Account Payable', 'icon' => 'check', 'url' => ['/accounting/invoice', 'type' => 10]],
            ['label' => 'Account Receivable', 'icon' => 'check', 'url' => ['/accounting/invoice', 'type' => 20]],
            ['label' => 'Payment', 'icon' => 'check',
                'items' => [
                    ['label' => 'Biaya-biaya', 'icon' => 'check', 'url' => ''],
                    ['label' => 'Pembelian Asset', 'icon' => 'check', 'url' => ''],
                    ['label' => 'Setoran Bank', 'icon' => 'check', 'url' => ''],
                    ['label' => 'Piutang Karyawan', 'icon' => 'check', 'url' => ''],
                    ['label' => 'Prive Owner', 'icon' => 'check', 'url' => ''],
                ],
            ],
        ],
    ],
    ['label' => 'Reports', 'icon' => 'pie-chart', 'iconOptions' => ['class' => 'text-aqua'], 'visible' => !Yii::$app->user->isGuest, 'url' => ['/report/report/index']],
    '<li class="header">ADMIN MENU</li>',
    ['label' => 'Setting', 'icon' => 'gears', 'iconOptions' => ['class' => 'text-orange'], 'visible' => !Yii::$app->user->isGuest && (in_array('admin.app', $temp_roles)),
        'items' => [
            ['label' => 'Users', 'icon' => 'check',
                'items' => [
                    ['label' => 'User List', 'icon' => 'check', 'url' => ['/admin/user']],
                    ['label' => 'U2Branch', 'icon' => 'check', 'url' => ['/master/u2-branch']],
                    ['label' => 'U2Warehouse', 'icon' => 'check', 'url' => ['/master/u2-warehouse']],
                ],
            ],
            ['label' => 'RBAC', 'icon' => 'check',
                'items' => [
                    ['label' => 'Routes', 'icon' => 'check', 'url' => ['/admin/route']],
                    ['label' => 'Rules', 'icon' => 'check', 'url' => ['/admin/rule']],
                    ['label' => 'Permissions', 'icon' => 'check', 'url' => ['/admin/permission']],
                    ['label' => 'Roles', 'icon' => 'check', 'url' => ['/admin/role']],
                    ['label' => 'Assignment', 'icon' => 'check', 'url' => ['/admin']],
                    ['label' => 'U2Branch', 'icon' => 'check'],
                    ['label' => 'U2Warehouse', 'icon' => 'check'],
                ],
            ],
        ]
    ],
    ['label' => 'Login', 'icon' => 'sign-in', 'iconOptions' => ['class' => 'text-green'], 'url' => ['/site/login'],
        'visible' => Yii::$app->user->isGuest],
    ['label' => 'Logout', 'icon' => 'sign-out', 'iconOptions' => ['class' => 'text-red'], 'url' => ['/site/logout'],
        'visible' => !Yii::$app->user->isGuest],
];
