<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Orgn */

$this->title = 'Buat Organisasi';
$this->params['breadcrumbs'][] = ['label' => 'Orgns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row orgn-create">
    <div class="col-lg-5">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>
</div>
