<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\PaymentMethod */

$this->title = 'Create Payment Method';
$this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-method-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
