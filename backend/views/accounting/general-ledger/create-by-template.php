<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\GlHeader */

$this->title = 'Create Journal by Template';
$this->params['breadcrumbs'][] = ['label' => 'General Ledger', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gl-header-create">

    <?= $this->render('_form-by-template', [
        'model' => $model,
    ]) ?>

</div>
