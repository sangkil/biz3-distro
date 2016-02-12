<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\accounting\EntriSheet */

$this->title = 'Create Entri Sheet';
$this->params['breadcrumbs'][] = ['label' => 'Entri Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entri-sheet-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
