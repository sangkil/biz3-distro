<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\purchase\Purchase */

$this->title = 'Create Purchase';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
