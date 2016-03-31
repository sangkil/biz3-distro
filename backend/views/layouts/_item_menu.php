<?php
return[
    '<li class="header">MAIN NAVIGATION</li>',
    ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site/index']],
    ['label' => 'Data Master', 'icon' => 'th',
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
    ['label' => 'Sales', 'icon' => 'shopping-cart', 'iconOptions' => ['class' => 'text-orange'],
        'items' => [
            ['label' => 'Sales Order', 'icon' => 'check', 'url' => ['/sales/sales/create']],
            ['label' => 'Sales Return', 'icon' => 'check',],
            ['label' => 'Price Mngnt', 'icon' => 'check',
                'items' => [
                    ['label' => 'Pricing Category', 'icon' => 'check', 'url' => ['/sales/price-category']],
                    ['label' => 'Sales Pricing', 'icon' => 'check', 'url' => ['/sales/price']],
                ],
            ],
        ],
    ],
    ['label' => 'Warehouse Mangmnt', 'icon' => 'truck', 'iconOptions' => ['class' => 'text-success'],
        'items' => [
            ['label' => 'Goods Receive', 'icon' => 'check', 'url' => ['/inventory/gm-manual', 'GoodsMovement[type]' => 10]],
            ['label' => 'Goods Issue', 'icon' => 'check', 'url' => ['/inventory/gm-manual', 'GoodsMovement[type]' => 20]],
            ['label' => 'Stock Transfer', 'icon' => 'check', 'url' => ['/inventory/transfer']],
            ['label' => 'Stock Management', 'icon' => 'check',
                'items' => [
                    ['label' => 'Stock', 'icon' => 'check', 'url' => ['/inventory/product-stock']],
                    ['label' => 'Stock History', 'icon' => 'check', 'url' => ['/inventory/stock-history']],
                    ['label' => 'Total Opname', 'icon' => 'check', 'url' => ''],
                    ['label' => 'Stock Adjustment', 'icon' => 'check', 'url' => ''],
                ],
            ],
        ],
    ],
    ['label' => 'FI & Accounting', 'icon' => 'buysellads', 'iconOptions' => '',
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
            ['label' => 'Invoices', 'icon' => 'check',
                'items' => [
                    ['label' => 'Incoming', 'icon' => 'check', 'url' => ['/accounting/invoice', 'invoice[type]' => 10]],
                    ['label' => 'Outgoing', 'icon' => 'check', 'url' => ['/accounting/invoice', 'invoice[type]' => 20]],
                ],
            ],
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
    ['label' => 'Report', 'icon' => 'pie-chart', 'iconOptions' => ['class' => 'text-aqua'],
        'items' => [
            ['label' => 'Biaya-biaya', 'icon' => 'check', 'url' => ''],
            ['label' => 'Pembelian Asset', 'icon' => 'check', 'url' => ''],
            ['label' => 'Setoran Bank', 'icon' => 'check', 'url' => ''],
            ['label' => 'Piutang Karyawan', 'icon' => 'check', 'url' => ''],
            ['label' => 'Prive Owner', 'icon' => 'check', 'url' => ''],
        ]
    ]
];