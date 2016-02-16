<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\AccPeriode */

$this->title = 'Closing ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Acc Periodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6 acc-periode-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'DateFrom',
            'DateTo',
            'nmStatus',
        ],
    ])
    ?>

</div>
<?php $form = ActiveForm::begin(['method' => 'post']); ?>
<div class="nav-tabs-justified col-lg-12" style="margin-top: 20px;">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#items" data-toggle="tab" aria-expanded="false">Automatic Proccess</a></li>
        <li class="pull-right">    
            <?= Html::submitButton(($model->status == $model::STATUS_CLOSE) ? 'Reverse' : 'Close', ['class' => ($model->status == $model::STATUS_CLOSE) ? 'btn btn-warning' : 'btn btn-primary']) ?>
        </li>             
    </ul> 
    <div class="tab-content" >
        <div class="tab-pane active col-lg-12" id="items" style="padding-top: 20px;">
            <?= Html::checkbox('stok', true, ['label' => 'Hitung ulang nilai stok']) . '<br>' ?>            
            <?= Html::checkbox('saldo', true, ['label' => 'Hitung ulang saldo akun']) . '<br>' ?>            
            <?= Html::checkbox('jurnal', true, ['label' => 'Buat jurnal penutup']) . '<br>' ?>          
            <?= Html::checkbox('newperiode', true, ['label' => 'Open periode baru']) ?>
            <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
        </div>
    </div> 
</div>
<?php ActiveForm::end(); ?>