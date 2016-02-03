<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\sales\Price */

$this->title = 'Create Price';
$this->params['breadcrumbs'][] = ['label' => 'Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
