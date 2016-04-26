<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Vendor */

$this->title = 'Create '.($model->nmType ? ucfirst(strtolower($model->nmType)) : 'Vendor');
$this->params['breadcrumbs'][] = ['label' => 'Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row vendor-create">
    <div class="col-lg-5">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>
</div>
