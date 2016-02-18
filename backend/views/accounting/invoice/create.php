<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Invoice */

$this->title = $model->nmType.' Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12 invoice-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
