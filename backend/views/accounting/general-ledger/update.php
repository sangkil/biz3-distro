<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\GlHeader */

$this->title = 'Update Gl Header: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gl Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gl-header-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
