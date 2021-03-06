<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Orgn */

$this->title = 'Orgn: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Orgns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgn-view">

    <p class="pull-right">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'template' => '<tr><th style="width:30%;">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            'id',
            'code',
            'name',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ])
    ?>

</div>
