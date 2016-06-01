<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\inventory\GoodsMovement;

/* @var $this yii\web\View */
/* @var $model GoodsMovement */

$this->title = 'Goods Movement';
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('print();');
?>
<div class="goods-movement-view col-lg-9" style="background-color: white; page-break-after: true; min-height: 600px;">
        <div class="col-lg-12">
        <table border="0" style="width: 100%;">
            <tbody>
                <tr>
                    <td>
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table'],
                            'template' => '<tr><th style="width:30%;">{label}</th><td>{value}</td></tr>',
                            'attributes' => [
                                'number',
                                [
                                    'attribute' => 'nmType',
                                    'label' => 'Mvmnt Type'
                                ],
                                [
                                    'attribute' => 'vendor.name',
                                    'label' => 'Vendor'
                                ],
                            ],
                        ])
                        ?>
                    </td>
                    <td style="padding-left: 20px;">
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table'],
                            'template' => '<tr><th style="width:30%;">{label}</th><td>{value}</td></tr>',
                            'attributes' => [
                                'Date',
                                [
                                    'attribute' => 'warehouse.name',
                                    'label' => 'Warehouse'
                                ],
                                //'description',
                                'nmStatus',
                            ],
                        ])
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-12"  style="margin-top: 20px;">
        <?=
        GridView::widget([
            'dataProvider' => new yii\data\ActiveDataProvider([
                'query' => $model->getItems()->with(['product', 'uom']),
                'pagination' => false,
                'sort' => false,
                ]),
            'tableOptions' => ['class' => 'table table-hover'],
            'layout' => '{items}',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'product.code',
                    'header' => 'Code'
                ],
                [
                    'attribute' => 'product.name',
                    'header' => 'Product Name'
                ],
                [
                    'attribute' => 'cogs',
                    'header' => 'Cost'
                ],
                'qty',
                [
                    'attribute' => 'uom.code',
                    'header' => 'Uom'
                ],
            ]
        ])
        ?>
    </div>
</div>
