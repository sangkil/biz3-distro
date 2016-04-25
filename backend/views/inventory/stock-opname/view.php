<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\inventory\StockOpname;

/* @var $this yii\web\View */
/* @var $model StockOpname */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?php if ($model->status == StockOpname::STATUS_DRAFT): ?>
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
            Html::a('Apply', ['confirm', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => 'Are you sure you want to apply this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php endif; ?>
    </p>

    <?=
    DetailView::widget([
        'options' => ['class' => 'table table-hover'],
        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            'number',
            'warehouse.name',
            'date',
            'nmStatus',
            'description',
            'operator',
        ],
    ])
    ?>

    <?php
    $query = (new \yii\db\Query())
        ->select(['p.id', 'p.code', 'p.name', 'o_qty' => 'COALESCE(o.qty,0)', 's_qty' => 'COALESCE(s.qty,0)',
            'selisih' => 'COALESCE(o.qty,0)-COALESCE(s.qty,0)'])
        ->from(['p' => '{{%product}}'])
        ->leftJoin(['s' => '{{%product_stock}}'], '[[s.product_id]]=[[p.id]] and [[s.warehouse_id]]=:whse', [':whse' => $model->warehouse_id])
        ->leftJoin(['o' => '{{%stock_opname_dtl}}'], '[[o.product_id]]=[[p.id]] and [[o.opname_id]]=:opid', [':opid' => $model->id])
        ->orderBy(['abs(COALESCE(o.qty,0)-COALESCE(s.qty,0))' => SORT_DESC, 'COALESCE(o.qty,0)' => SORT_DESC]);

    echo GridView::widget([
        'dataProvider' => new yii\data\ActiveDataProvider([
            'query' => $query,
            ]),
        'tableOptions' => ['class' => 'table table-hover'],
        'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'code',
                'header' => 'Code'
            ],
            [
                'attribute' => 'name',
                'header' => 'Product Name'
            ],
            [
                'attribute' => 'o_qty',
                'header' => 'Opname Stock'
            ],
            [
                'attribute' => 's_qty',
                'header' => 'System Stock'
            ],
            [
                'attribute' => 'selisih',
                'header' => 'Selisih'
            ],
        ]
    ])
    ?>
</div>
