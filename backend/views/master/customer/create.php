<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\Vendor */

$this->title = 'Create Vendor';
$this->params['breadcrumbs'][] = ['label' => 'Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
