<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\inventory\Transfer;

/* @var $this yii\web\View */
/* @var $model Transfer */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Transfers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-view">
    <div class="col-lg-12">
        <p class="pull-right">
            <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
            <?php
            if ($model->status == Transfer::STATUS_DRAFT) {
                echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
            }
            ?>
            <?php
            if ($model->status == Transfer::STATUS_DRAFT) {
                echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
            <?php
            if ($model->status == Transfer::STATUS_DRAFT) {
                echo Html::a('Confirm', ['confirm', 'id' => $model->id], [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'confirm' => 'Are you sure you want to confirm this item?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
            <?php
            if ($model->status == Transfer::STATUS_RELEASED) {
                echo Html::a('Create Issue', ['inventory/gm-from-reff/create', 'type' => Transfer::REFF_SELF, 'id' => $model->id], [
                    'class' => 'btn btn-success',
                ]);
            }
            ?>
            <?php
            if ($model->status == Transfer::STATUS_RELEASED) {
                echo Html::a('Cancel', ['reject', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to cancel this item?',
                        'method' => 'post',
                    ],
                ]);
            }
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
                    'attribute' => 'branch.name',
                    'label' => 'Source'
                ],
                [
                    'attribute' => 'branchDest.name',
                    'label' => 'Destination'
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
                    'label' => 'Status',
                    'attribute' => 'nmStatus',
                    'format' => 'raw',
                    'value' => ($model->status == $model::STATUS_DRAFT) ? '<span class="badge bg-yellow">' . $model->nmStatus . '</span>'
                            : (($model->status == $model::STATUS_CLOSED) ? '<span class="badge bg-red">' . $model->nmStatus . '</span>'
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
                GridView::widget([
                    'dataProvider' => new yii\data\ActiveDataProvider([
                        'query' => $model->getMovements(),
                        'pagination' => false,
                        'sort' => false,
                        ]),
                    'tableOptions' => ['class' => 'table table-hover'],
                    'layout' => '{items}',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'header' => 'Number',
                            'value' => function($model) {
                                return Html::a($model->number, ['inventory/gm-from-reff/view', 'id' => $model->id]);
                            },
                                'format' => 'raw'
                            ],
                            [
                                'header' => 'Status',
                                'attribute' => 'nmStatus'
                            ]
                        ]
                    ])
                    ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12">

    </div>
</div>
