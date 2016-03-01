<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Coa */

$this->title = 'Create Coa';
$this->params['breadcrumbs'][] = ['label' => 'Coas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coa-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
