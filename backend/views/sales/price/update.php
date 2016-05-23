<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Price */

$this->title = 'Update Price: ' . ' ' . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_id, 'url' => ['view', 'product_id' => $model->product_id, 'price_category_id' => $model->price_category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="price-update">
    <div class="col-lg-5">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>

</div>
