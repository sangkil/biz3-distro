<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\inventory\Transfer */

$this->title = 'Create Transfer';
$this->params['breadcrumbs'][] = ['label' => 'Transfers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
