<?php

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
<div class="row sales-view">
    <div class="col-lg-12">
        <p class="pull-right">
            <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
            <?=
                Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ])
            ?>
            <?php echo ($model->status <= Sales::STATUS_DRAFT) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default'])
                : Html::a('<i class="fa fa-print"></i>', ['cetak', 'id' => $model->id], ['class' => 'btn btn-default','target'=>'_blank']) ?>
            <?php
            echo ($model->status <= Sales::STATUS_DRAFT)?
            Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]):'';
            ?>
        </p>
    </div>
    <div class="col-lg-6">
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table'],
            'template' => '<tr><th style="width:30%;">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'number',
                [                      // the owner name of the model
                    'label' => 'Branch',
                    'value' => $model->branch->name,
                ],
                [                      // the owner name of the model
                    'label' => 'Customer',
                    'format' => 'raw',
                    'value' => $model->vendor->name,
                ],
            ],
        ])
        ?>
    </div>
    <div class="col-lg-6">
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table'],
            'template' => '<tr><th style="width:30%;">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'Date',
                [                      // the owner name of the model
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => ($model->status == $model::STATUS_DRAFT) ? '<span class="badge bg-yellow">' . $model->nmStatus . '</span>'
                            : '<span class="badge bg-green">' . $model->nmStatus . '</span>'
                ],
            ],
        ])
        ?>
    </div>
    <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href = "#notes" data-toggle = "tab" aria-expanded = "false">Notes</a></li>
        </ul>
        <div class = "tab-content" >
            <div class = "tab-pane active" id = "item">
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
                        [
                            'attribute' => 'product.code',
                            'header' => 'Code'
                        ],
                        [
                            'attribute' => 'product.name',
                            'header' => 'Product Name'
                        ],
                        [
                            'attribute' => 'price',
                            'header' => 'Sales Price',
                            'format' => ['decimal', 0]
                        ],
                        'qty',
                        [
                            'attribute' => 'uom.code',
                            'header' => 'Uom'
                        ],
                        [
                            'attribute' => 'discount',
                            'header' => 'Discount(%)',
                            'footer' => 'Total'
                        ],
                        [
                            'header' => 'Total Line',
                            'value' => function ($model) {
                                $b4diskon = $model->price * $model->qty * $model->productUom->isi;
                                $afdiskon = $b4diskon * (1 - $model->discount / 100);
                                return $afdiskon;
                            },
                            'format' => ['decimal', 0],
                            'footer' => number_format($totaLine, 0)
                        ],
                    ]
                ])
                ?>
            </div>
            <div class="tab-pane" id="notes">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table'],
                    'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        'created_at:datetime',
                        'created_by',
                        'updated_at:datetime',
                        'updated_by',
                    ],
                ])
                ?>
            </div>
        </div>


    </div>
