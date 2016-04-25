<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Orgn */

$this->title = 'Update Orgn: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Orgns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row orgn-update">
    <div class="col-lg-5">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>

</div>
