<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\Orgn */

$this->title = 'Create Orgn';
$this->params['breadcrumbs'][] = ['label' => 'Orgns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgn-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
