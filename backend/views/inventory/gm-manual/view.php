<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\inventory\GoodsMovement;

/* @var $this yii\web\View */
/* @var $model GoodsMovement */

$this->title = $model->nmType . ' #' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-view">
    <div class="col-lg-12">
        <p class="pull-right">
            <?= (!$model->isNewRecord) ? Html::a('Create New', ['create', 'type' => $model->type],['class'=>'btn btn-default']) :'' ?>
            <?php if ($model->status == GoodsMovement::STATUS_DRAFT): ?>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
                <?=
                Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ])
                ?>
                <?=
                Html::a('Confirm', ['confirm', 'id' => $model->id], [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'confirm' => 'Are you sure you want to confirm this item?',
                        'method' => 'post',
                    ],
                ])
                ?>
            <?php elseif ($model->status == GoodsMovement::STATUS_RELEASED): ?>
                <?=
                ($model->invoice == null || $model->invoice->status == \backend\models\accounting\Invoice::STATUS_CANCELED)
                        ? Html::a('Cancel', ['rollback', 'id' => $model->id], [
                        'class' => 'btn btn-warning',
                        'data' => [
                            'confirm' => 'Are you sure you want to rollback this item?',
                            'method' => 'post',
                        ],
                    ]) : '' . '&nbsp;'
                ?>
                <?=
                ($model->invoice == null) ?
                    Html::a('Create Invoice', ['accounting/invoice/create', 'Invoice[type]' => 10, 'goodsMovement[id]' => $model->id], [
                        'class' => 'btn btn-success',
//                    'data' => [
//                        'method' => 'get',
//                    ],
                    ]) : ''
                ?>
            <?php endif; ?>
            <?=
            (!$model->isNewRecord) ? Html::a('<i class="fa fa-print"></i>', ['print', 'id' => $model->id], [
                    'class' => 'btn btn-default',
                    'target' => '_blank',
                    'data' => [
                        'method' => 'post',
                    ],
                ]) : '';
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
                [
                    'attribute' => 'nmType',
                    'label' => 'Movement Type'
                ],
                [
                    'attribute' => 'vendor.name',
                    'label' => 'Vendor Name'
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
                [
                    'attribute' => 'warehouse.name',
                    'label' => 'Warehouse'
                ],
                //'description',
                //'nmStatus',
                [                      // the owner name of the model
                    'label' => 'Invoice',
                    'format' => 'raw',
                    'value' => ($model->invoice != null) ? Html::a($model->invoice->number, ['/accounting/invoice/view', 'id' => $model->invoice->id]):'',
                    'visible'=> ($model->invoice != null)
                ],
                [                      // the owner name of the model
                    'label' => 'Status',
                    'attribute' => 'nmStatus',
                    'format' => 'raw',
                    'value' => ($model->status == $model::STATUS_DRAFT) ? '<span class="badge bg-yellow">' . $model->nmStatus . '</span>'
                            : (($model->status == $model::STATUS_CANCELED) ? '<span class="badge bg-red">' . $model->nmStatus . '</span>'
                                : '<span class="badge bg-green">' . $model->nmStatus . '</span>')
                ],
            ],
        ])
        ?>
    </div>
    <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
        </ul>
        <div class="tab-content" >
            <div class="tab-pane active" id="item">
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

    <div class="col-lg-12">

    </div>
</div>
