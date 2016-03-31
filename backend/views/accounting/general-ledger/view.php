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
            <?= Html::a('New Journal', ['create'], ['class' => 'btn btn-default']) ?>
            <?= ($model->status < $model::STATUS_RELEASED) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) : '' ?>
            <?=
            ($model->status == $model::STATUS_RELEASED) ?
                    Html::a('Cancel', ['reverse', 'id' => $model->id], [
                        'class' => 'btn btn-warning', 'data' => [
                            'confirm' => 'Are you sure you want to cancel this item?',
                            'method' => 'post',
                ]]) : ''
            ?>
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
                //'id',
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
                [                      // the owner name of the model
                    'label' => 'Type/Number',
                    'format' => 'raw',
                    'value' => $model->nmReffType.'/'.$model->reffNumber
                ],
//                [                      // the owner name of the model
//                    'label' => 'Reff Number',
//                    'format' => 'raw',
//                    'value' => $model->reffNumber
//                ],
                'description',
                [                      // the owner name of the model
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => ($model->status == $model::STATUS_DRAFT) ? '<span class="badge bg-yellow">' . $model->nmStatus . '</span>' : (($model->status == $model::STATUS_CANCELED) ? '<span class="badge bg-red">' . $model->nmStatus . '</span>' : '<span class="badge bg-green">' . $model->nmStatus . '</span>')
                ],
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
                    $inputArea .= Html::tag('th', 'Debit', ['style' => 'width:16%;background-color:#FAFAFA; border-right:2px solid white;']);
                    $inputArea .= Html::tag('th', 'Credit', ['style' => 'width:16%;background-color:#FAFAFA;']);
                    $inputArea .= Html::endTag('tr');
                    $inputArea .= Html::endTag('thead');
                    echo $inputArea;
                    ?>
                    <?=
                    TabularInput::widget([
                        'id' => 'detail-grid',
                        'allModels' => $model->glDetails,
                        'model' => GlDetail::className(),
                        'tag' => 'tbody',
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
