<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\EntriSheet */

$this->title = 'Update Entri Sheet: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Entri Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entri-sheet-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
