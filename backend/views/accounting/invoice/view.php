<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Invoice */

$this->title = $model->nmType . ' Invoice #' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-view">
    <div class="col-lg-12" style="margin-bottom:10px;">
        <div class="pull-right">

            <div class='btn-group pull-right'>
                <?= Html::button('New Invoice', ['class' => 'btn btn-default', 'type' => 'button']) ?>        
                <?= Html::button('<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>', ['class' => 'btn btn-default dropdown-toggle', 'aria-expanded' => false, 'type' => 'button', 'data-toggle' => 'dropdown']) ?>
                <ul class="dropdown-menu" role="menu">
                    <li><?= Html::a('Incoming', ['create', 'Invoice[type]' => $searchModel::TYPE_INCOMING]) ?></li>
                    <li><?= Html::a('Outgoing', ['create', 'Invoice[type]' => $searchModel::TYPE_OUTGOING]) ?></li>            
                </ul>        
            </div>
            <?= ($model->status == $model::STATUS_DRAFT) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) : '' ?>
            <?=
            ($model->status == $model::STATUS_DRAFT) ? Html::a('Post', ['post', 'id' => $model->id], [
                        'class' => 'btn btn-primary',
                        'data' => [
                            'confirm' => 'Are you sure you want to post this Invoice?',
                            'method' => 'post',
                        ],
                    ]) : ''
            ?>
            <?=
            ($model->status == $model::STATUS_POSTED && $model->journals == null) ? Html::a('Revert', ['revert', 'id' => $model->id], [
                        'class' => 'btn btn-warning',
                        'data' => [
                            'confirm' => 'Are you sure you want to revert this Invoice?',
                            'method' => 'post',
                        ],
                    ]) : ''
            ?>
            <?=
            ($model->status == $model::STATUS_DRAFT) ? Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) : ''
            ?> 
        </div>
    </div>    
    <div class="col-lg-6">
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table'],
            'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'number',
                'vendor.name',
                'date',
                'due_date'
            ],
        ])
        ?>
    </div><div class="col-lg-6">
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table'],
            'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'value',
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
            <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#journals" data-toggle="tab" aria-expanded="false">Journals</a></li>            
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>            
        </ul>
        <div class="tab-content" >
            <div class="tab-pane active" id="item">
                <?= $this->render('_detail_view', ['model' => $model]) ?>
            </div>
            <div class="tab-pane" id="journals">                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 10%">
                                Gl Date
                            </th>
                            <th class="items" style="width: 10%">
                                GL Number
                            </th>
                            <th>
                                Descripton
                            </th>
                        </tr>
                    </thead>
                    <?php
                    $z = 1;
                    foreach ($model->journals as $row) {
                        $link = Html::a($row->number, ['/accounting/general-ledger/view', 'id' => $row->id], ['target' => '_blank']);
                        echo "<tr><td>{$z}</td><td>{$row->date}</td>"
                        . "<td>{$link}</td>"
                        . "<td>{$row->description}</td></tr>";
                        $z++;
                    }
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
                        'created_by',
                        'created_at:datetime',
                        'updated_by',
                        'updated_at:datetime',
                    ],
                ])
                ?>
            </div>
        </div>
    </div>
</div>
