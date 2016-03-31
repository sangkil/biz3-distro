<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Invoice;
use backend\models\accounting\InvoiceDtl;

/* @var $this View */
/* @var $model Invoice */
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th>
                Item Name
            </th>
            <th class="items" style="text-align:right;width: 10%">
                Qty
            </th>
            <th style="text-align:right;width: 15%">
                Unit Cost
            </th>
            <th style="text-align:right; width: 15%">
                Line Total
            </th>
        </tr>
        <tr>
            <td colspan="3">
                <div class="input-group" style="width: 100%; z-index: ">
                    <div class="input-group-btn">
                        <!--<button type="button" class="btn btn-default" id="selected_type"><i class="fa fa-search"></i></button>-->
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" data-field="search_type" data-value="10" data-placehold="Search by item code/name" class="searchtype">Item Detail</a></li>
                            <li><a href="#" data-field="search_type" data-value="20" data-placehold="Search by goods movement number" class="searchtype">Goods Movements</a></li>
                            <li><a href="#" data-field="search_type" data-value="30" data-placehold="Search by sales number" class="searchtype">Sales</a></li>
                        </ul>
                    </div>
                    <input id="input-product" data-field="item_search" class="form-control" placeholder="Search by item code/name" style="z-index: 0;">
                </div>
            </td>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'model' => InvoiceDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
