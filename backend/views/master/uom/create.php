<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\Uom */

$this->title = 'Create Uom';
$this->params['breadcrumbs'][] = ['label' => 'Uoms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uom-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
