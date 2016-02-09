<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\widgets\TabularInput;
use backend\models\accounting\GlDetail;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\GlHeader */

$this->title = 'Journal Detail #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gl Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gl-header-view">


    <div class="col-lg-12">
        <p class="pull-right">
            <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
            <?= ($model->status < $model::STATUS_RELEASED) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) : '' ?>
            <?=
            ($model->status < $model::STATUS_RELEASED) ?
                    Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) : ''
            ?>
        </p>  
    </div>
    <div class="col-lg-6">
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table'],
            'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'id',
                'number',
                'GlDate',
                [
                    'label' => 'Acc Periode',
                    'attribute' => 'periode.name'
                ],
                [
                    'label' => 'Orgn Branch',
                    'attribute' => 'branch.name'
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
            'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'reff_type',
                'reff_id',
                'description',
                'nmStatus',
            ],
        ])
        ?>
    </div>    
    <div class="nav-tabs-justified col-lg-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#items" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>        
            <li class="pull-right">    
                <?= ''//Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])   ?>
            </li>             
        </ul> 
        <div class="tab-content" >
            <div class="tab-pane active" id="items">
                <table class="table table-hover">
                    <?php
                    $inputArea = Html::beginTag('thead');
                    $inputArea .= Html::beginTag('tr');
                    $inputArea .= Html::tag('th', 'Code', ['style' => 'width:20%;']);
                    $inputArea .= Html::tag('th', 'Account Name');
                    $inputArea .= Html::tag('th', 'Debit', ['style' => 'width:16%;']);
                    $inputArea .= Html::tag('th', 'Credit', ['style' => 'width:16%;']);
                    $inputArea .= Html::endTag('tr');
                    $inputArea .= Html::endTag('thead');
                    echo $inputArea;
                    ?>
                    <?=
                    TabularInput::widget([
                        'id' => 'detail-grid',
                        'allModels' => $model->glDetails,
                        'modelClass' => GlDetail::className(),
                        'options' => ['tag' => 'tbody'],
                        'itemOptions' => ['tag' => 'tr'],
                        'itemView' => '_item_view',
                        'clientOptions' => [
                        ]
                    ])
                    ?>
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

</div>
