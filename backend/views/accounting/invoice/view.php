<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Invoice */

$this->title = ucfirst(strtolower($model->nmType)) . ' Invoice #' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-view">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-12" style="margin-bottom:10px;">
        <div class="pull-right">
            <div class='btn-group'>
                <?= Html::button('New Invoice', ['class' => 'btn btn-default', 'type' => 'button']) ?>        
                <?=
                ($model->status >= $model::STATUS_RELEASED && $model->sisa > 0) ? Html::a('Create Payment', ['/accounting/payment/create',
                        'invoice_id' => $model->id], ['class' => 'btn btn-success', 'type' => 'button']) : ''
                ?>
            </div>
            <?=
            ($model->status == $model::STATUS_DRAFT) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default'])
                    : ''
            ?>
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
            ($model->status == $model::STATUS_RELEASED && $model->journals == null && $model->payments == null) ? Html::a('Revert', ['revert',
                    'id' => $model->id], [
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
                'Date',
                'DueDate'
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
                [                      // the owner name of the model
                    'label' => 'Amount',
                    'attribute' => 'value',
                    'format' => ['decimal', 0]
                ],
                'description',
                [                      // the owner name of the model
                    'label' => 'Reff Type/Num',
                    'format' => 'raw',
                    'value' => $model->nmReffType . '/' . $model->reffNumber
                ],
                [                      // the owner name of the model
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => ($model->status == $model::STATUS_DRAFT) ? '<span class="badge bg-yellow">' . $model->nmStatus . '</span>'
                            : (($model->status == $model::STATUS_CANCELED) ? '<span class="badge bg-red">' . $model->nmStatus . '</span>'
                                : '<span class="badge bg-green">' . $model->nmStatus . '</span>')
                ],
            ],
        ])
        ?>
    </div>
    <div class="nav-tabs-justified col-lg-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#payments" data-toggle="tab" aria-expanded="false">Payments</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>            
        </ul>
        <div class="tab-content" >
            <div class="tab-pane active" id="item">
<?= $this->render('_detail_view', ['model' => $model]) ?>
            </div>
            <div class="tab-pane" id="payments">
                <table border="0" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paymnt Number</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $tpyval = 0;
                        foreach ($model->payments as $payment) {
                            echo Html::beginTag('tr');
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . yii\bootstrap\Html::a($payment->number, ['/accounting/payment/view', 'id' => $payment->id]) . '</td>';
                            echo '<td>' . $payment->date . '</td>';
                            echo '<td>' . $payment->nmStatus . '</td>';
                            echo '<td>' . $payment->paymentMethod->method . '</td>';
                            echo Html::beginTag('td');
                            $pyval = 0;
                            foreach ($payment->items as $item) {
                                $pyval += $item->value;
                            }
                            echo number_format($pyval, 0);
                            echo Html::endTag('td');
                            echo Html::endTag('tr');
                            $tpyval += $pyval;
                            $i++;
                        }
                        echo Html::beginTag('tr');
                        echo '<td colspan="5"></td>';
                        echo Html::beginTag('td', ['style' => 'font-weight:bold;']);
                        echo number_format($tpyval, 0);
                        echo Html::endTag('td');
                        echo Html::endTag('tr');
                        ?>
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
                        'created_by',
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
<?php ActiveForm::end(); ?>
</div>
