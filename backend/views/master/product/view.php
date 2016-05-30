<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="pull-right">
    <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
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
</p>
<div class="col-lg-12"></div>
<div class="product-view col-lg-6">
    <?=
    DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-hover'],
        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            //'id',
            'code',
            'name',
            [
                'label' => 'Product Group',
                'value' => $model->group->name
            ],
            [
                'label' => 'Category',
                'value' => $model->category->name
            ],
            'Edition',
            'stockable:boolean'
        ],
    ])
    ?>
</div>
<div class="product-view col-lg-6">
    <?=
    DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-hover'],
        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            'nmStatus',
            'created_at:datetime',
            'created_by',
            'updated_at:datetime',
            'updated_by',
        ],
    ])
    ?>
</div>
<div class="col-lg-12">
    <div class="nav-tabs-justified">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#uom" data-toggle="tab" aria-expanded="false">Uoms</a></li>
            <li><a href="#bcode" data-toggle="tab" aria-expanded="false">Barcodes Alias</a></li>    
            <li><a href="#dprice" data-toggle="tab" aria-expanded="false">Sales Price</a></li>             
        </ul> 
        <div class="tab-content" >
            <div class="tab-pane active" id="uom">
                <table class="table table-hover no-padding" style="width: 60%;">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 40%;">Uom Code</th>
                            <th>Isi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $row = '';
                        $i = 1;
                        foreach ($model->productUoms as $roums) {
                            $row .= '<tr>';
                            $row .= '<td>' . $i . '</td>';
                            $row .= '<td>' . $roums->uom->code . '</td>';
                            $row .= '<td>' . $roums->isi . '</td>';
                            $row .= '</tr>';
                            $i++;
                        }
                        echo $row;
                        ?> 
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="bcode">
                <table class="table table-hover" style="width: 60%;">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th>Barcodes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $row = '';
                        $i = 1;
                        foreach ($model->productChildren as $bcode) {
                            $row .= '<tr>';
                            $row .= '<td>' . $i . '</td>';
                            $row .= '<td>' . $bcode->barcode . '</td>';
                            $row .= '</tr>';
                            $i++;
                        }
                        echo $row;
                        ?> 
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="dprice">
                <table class="table table-hover" style="width: 60%;">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th>Price Category</th>
                            <th>Sales Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $row = '';
                        $i = 1;
                        foreach ($model->prices as $dprc) {
                            $row .= '<tr>';
                            $row .= '<td>' . $i . '</td>';
                            $row .= '<td>' . $dprc->priceCategory->name . '</td>';
                            $row .= '<td>' . number_format($dprc->price,0) . '</td>';
                            $row .= '</tr>';
                            $i++;
                        }
                        echo $row;
                        ?> 
                    </tbody>
                </table>
            </div>
        </div> 
    </div>    
</div>

