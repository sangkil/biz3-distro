<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\AccPeriode */

$this->title = 'Create Acc Periode';
$this->params['breadcrumbs'][] = ['label' => 'Acc Periodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acc-periode-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
