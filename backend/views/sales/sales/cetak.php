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
<div class="sales-view col-lg-2">
    <?=
    DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table no-border'],
        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
        'attributes' => [
            'number',
            'created_at:datetime',
            [
                'label' => 'Kasir',
                'attribute' => 'kasir.username'
            ]
        ],
    ])
    ?>
    <?php
    $totaLine = 0;
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
        }
    }
    echo GridView::widget([
        'dataProvider' => $dtPro,
        'tableOptions' => ['class' => 'table table-hover'],
        'layout' => '{items}',
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'header' => 'Product Name'
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
                'header' => 'Total Line',
                'value' => function ($model) {
                    $b4diskon = $model->price * $model->qty * $model->productUom->isi;
                    $afdiskon = $b4diskon; //$b4diskon * (1 - $model->discount / 100);
                    return $afdiskon;
                },
                'format' => ['decimal', 0],
                'footer' => number_format($totaLine, 0)
            ],
        ]
    ])
    ?>
</div>


