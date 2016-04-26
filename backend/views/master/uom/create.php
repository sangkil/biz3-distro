<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\Uom */

$this->title = 'Buat Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Uoms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row uom-create">
    <div class="col-lg-5">
        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
