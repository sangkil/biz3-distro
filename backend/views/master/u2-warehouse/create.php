<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\master\U2Warehouse */

$this->title = 'Create U2 Warehouse';
$this->params['breadcrumbs'][] = ['label' => 'U2 Warehouses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row u2-warehouse-create">

    <div class="col-lg-5">
        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    

</div>
