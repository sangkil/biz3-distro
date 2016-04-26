<?php
$temp_roles = [];
$user_roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
foreach ($user_roles as $key => $value) {
    $temp_roles[] = $key;
}

return[
    '<li class="header">MAIN NAVIGATION</li>',
    ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site/index'], 'visible' => !Yii::$app->user->isGuest],
    ['label' => 'Laporan', 'icon' => 'pie-chart', 'iconOptions' => ['class' => 'text-aqua'], 'visible' => !Yii::$app->user->isGuest,
        'url' => ['/report/report/index']],
    ['label' => 'Master Data', 'icon' => 'th', 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'Organisasi', 'icon' => 'check', 'url' => ['/master/orgn']],
            ['label' => 'Cabang', 'icon' => 'check', 'url' => ['/master/branch']],
            ['label' => 'Gudang/Showroom', 'icon' => 'check', 'url' => ['/master/warehouse']],
            ['label' => 'Master Barang', 'icon' => 'check',
                'items' => [
                    ['label' => 'Detail Barang', 'icon' => 'check', 'url' => ['/master/product']],
                    ['label' => 'Satuan', 'icon' => 'check', 'url' => ['/master/uom']],
                    ['label' => 'Group Barang', 'icon' => 'check', 'url' => ['/master/product-group']],
                    ['label' => 'Kategori Barang', 'icon' => 'check', 'url' => ['/master/category']],
                ],
            ],
            ['label' => 'Pemasok', 'icon' => 'check', 'url' => ['/master/vendor', 'type' => 10]],
            ['label' => 'Pelanggan', 'icon' => 'check', 'url' => ['/master/vendor', 'type' => 20]],
        ],
    ],
    ['label' => 'Penjualan', 'icon' => 'shopping-cart', 'iconOptions' => ['class' => 'text-orange'], 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'Penjualan Ecer', 'icon' => 'check', 'url' => ['/sales/sales/create']],
            ['label' => 'Manj Harga', 'icon' => 'check',
                'items' => [
                    ['label' => 'Kategori Harga', 'icon' => 'check', 'url' => ['/sales/price-category']],
                    ['label' => 'Harga Jual', 'icon' => 'check', 'url' => ['/sales/price']],
                ],
            ],
            ['label' => 'Manj Diskon', 'icon' => 'check',
                'items' => [
                    ['label' => 'Tipe Diskon', 'icon' => 'check', 'url' => '#'],
                    ['label' => 'Diskon Penjualan', 'icon' => 'check', 'url' => '#'],
                ],
            ],
            ['label' => 'Klosing Harian', 'icon' => 'check', 'url' => ['/sales/sales/create']],
        ],
    ],
    ['label' => 'Pengelolaan Persedian', 'icon' => 'truck', 'iconOptions' => ['class' => 'text-success'], 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'Penerimaan Barang', 'icon' => 'check', 'url' => ['/inventory/gm-manual', 'GoodsMovement[type]' => 10]],
            ['label' => 'Pengeluaran Barang', 'icon' => 'check', 'url' => ['/inventory/gm-manual', 'GoodsMovement[type]' => 20]],
            ['label' => 'Mutasi Persediaan', 'icon' => 'check', 'url' => ['/inventory/transfer']],
            ['label' => 'Opname', 'icon' => 'check', 'url' => ''],
            ['label' => 'Koreksi Persediaan', 'icon' => 'check', 'url' => ''],
        ],
    ],
    ['label' => 'Akunt Keuangan', 'icon' => 'buysellads', 'visible' => !Yii::$app->user->isGuest,
        'items' => [
            ['label' => 'COA', 'icon' => 'check', 'url' => ['/accounting/coa']],
            ['label' => 'Periode Akunt', 'icon' => 'check',
                'items' => [
                    ['label' => 'Periodes', 'icon' => 'check', 'url' => ['/accounting/acc-periode']],
                    ['label' => 'Closing', 'icon' => 'check', 'url' => ['/accounting/acc-periode/close']],
                ],
            ],
            ['label' => 'Jurnal Umum', 'icon' => 'check',
                'items' => [
                    ['label' => 'Jurnal Baru', 'icon' => 'check', 'url' => ['/accounting/general-ledger/create']],
                    ['label' => 'Template Jurnal', 'icon' => 'check', 'url' => ['/accounting/journal-template']],
                ],
            ],
            ['label' => 'Hutang Usaha', 'icon' => 'check', 'url' => ['/accounting/invoice', 'type' => 10]],
            ['label' => 'Piutang Usaha', 'icon' => 'check', 'url' => ['/accounting/invoice', 'type' => 20]],
            ['label' => 'Kas & Bank', 'icon' => 'check',
                'items' => [
                    ['label' => 'Penerimaan', 'icon' => 'check', 'url' => ['#']],
                    ['label' => 'Pengeluaran', 'icon' => 'check',
                        'items' => [
                            ['label' => 'Biaya-biaya', 'icon' => 'check', 'url' => ''],
                            ['label' => 'Pembelian Aset', 'icon' => 'check', 'url' => ''],
                            ['label' => 'Prive Owner', 'icon' => 'check', 'url' => ''],
                        ],
                    ],
                ],
            ],
        ],
    ],
    '<li class="header">ADMIN MENU</li>',
    ['label' => 'Setting', 'icon' => 'gears', 'iconOptions' => ['class' => 'text-orange'], 'visible' => !Yii::$app->user->isGuest
        && (in_array('admin.app', $temp_roles)),
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
