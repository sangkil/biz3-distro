<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\sales\Sales;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Sales */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Saless', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-view col-lg-3" style="background-color: white;">
    <br>
    <p class="text-center text-bold">FAKTUR PENJUALAN</p>
    <?=
    DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-condensed no-border'],
        'template' => '<tr><td>{label}</td><td>{value}</td></tr>',
        'attributes' => [
            //'created_at:datetime',
            [
                'label' => 'Nomer Faktur',
                'attribute' => 'number'
            ],
            [
                'label' => 'Kasir',
                'attribute' => 'kasir.username'
            ]
        ],
    ])
    ?>
    <?php
    $totaLine = 0;
    $totalDiskon = 0;
    $dtPro = new yii\data\ActiveDataProvider([
        'query' => $model->getItems()->with(['product', 'uom']),
        'pagination' => false,
        'sort' => false,
    ]);
    if (!empty($dtPro->getModels())) {
        foreach ($dtPro->getModels() as $key => $val) {
            $b4diskon = $val->price * $val->qty * $val->productUom->isi;
            $afdiskon = $b4diskon * (1 - $val->discount / 100);

            $totaLine += $afdiskon;
            $totalDiskon += $b4diskon - $afdiskon;
        }
    }
    echo GridView::widget([
        'dataProvider' => $dtPro,
        'tableOptions' => ['class' => 'table table-hover table-condensed'],
        'layout' => '{items}',
        'showFooter' => true,
        'footerRowOptions' => ['style' => 'font-weight:bold;text-align:right;'],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//                [
//                    'attribute' => 'product.code',
//                    'header' => 'Code'
//                ],
            [
                'attribute' => 'qty',
                'header' => 'Qty',
                'value' => function ($model) {
                    return $model->qty . ' ' . $model->uom->code;
                }
            ],
            [
                'attribute' => 'product.name',
                'header' => 'Product Name',
                'value' => function ($model) {
                    $split = explode(';', $model->product->name);
                    $newname = $split[1] . ';' . $split[2] . ';' . $split[0];
                    return (strlen($newname) > 26) ? substr($newname, 0, 26) . ' ..' : $newname;
                    // $model->qty . ' ' . $model->uom->code;
                },
                'footer' => ($totalDiskon > 0) ? 'Total <br>Diskon' : 'Total'
            ],
//            [
//                'attribute' => 'price',
//                'header' => 'Sales Price',
//                'format' => ['decimal', 0]
//            ],
//            [
//                'attribute' => 'discount',
//                'header' => 'Disc',
//                'footer' => 'Total'
//            ],
            [
                'header' => 'TotalLine',
                'value' => function ($model)use ($totaLine) {
                    $b4diskon = $model->price * $model->qty * $model->productUom->isi;
                    $afdiskon = $b4diskon; //$b4diskon * (1 - $model->discount / 100);
                    return $afdiskon;
                },
                'format' => ['decimal', 0],
                'contentOptions' => ['style' => 'text-align:right;'],
                'footer' => ($totalDiskon > 0) ? number_format(($totaLine + $totalDiskon), 0) . '<br>' . number_format(($totalDiskon), 0) : number_format(($totaLine + $totalDiskon), 0)
            ],
        ]
    ])
    ?>

</div>


