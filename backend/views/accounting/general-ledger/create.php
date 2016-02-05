<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\GlHeader */

$this->title = 'Create Gl Header';
$this->params['breadcrumbs'][] = ['label' => 'Gl Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gl-header-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
