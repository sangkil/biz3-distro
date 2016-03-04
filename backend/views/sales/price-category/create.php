<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\sales\PriceCategory */

$this->title = 'Create Price Category';
$this->params['breadcrumbs'][] = ['label' => 'Price Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
