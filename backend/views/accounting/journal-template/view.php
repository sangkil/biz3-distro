<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\widgets\TabularInput;
use backend\models\accounting\EntriSheetDtl;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\EntriSheet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Entri Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
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
</div>
<div class="col-lg-12 entri-sheet-view">
    <?=
    DetailView::widget([
        'options' => ['class' => 'table table-hover'],
        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'name'
        ],
    ])
    ?>
</div>
<div class="nav-tabs-justified col-lg-12">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#items" data-toggle="tab" aria-expanded="false">Journal Items</a></li>
        <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>        
        <li class="pull-right">    
            <?= ''//Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])   ?>
        </li>             
    </ul> 
    <div class="tab-content" >
        <div class="tab-pane active" id="items">
            <table class="table table-hover">
                <thead>
            <tr>
                <th>D/K</th>
                <th style="width: 20%">Code</th>
                <th>Account Name</th>
            </tr>
        </thead>
        <tbody>
            <tr data-key="d">
                <td>Debit</td>
                <td ><?= Html::getAttributeValue($model, 'dCoa[code]')?></td>
                <td><?= Html::getAttributeValue($model, 'dCoa[name]')?></td>
            </tr>
            <tr data-key="k">
                <td>Kredit</td>
                <td ><?= Html::getAttributeValue($model, 'kCoa[code]')?></td>
                <td><?= Html::getAttributeValue($model, 'kCoa[name]')?></td>
            </tr>
        </tbody>
            </table>
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
