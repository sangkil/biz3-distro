<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Branch */

$this->title = 'Update U2 Branch: ' . ' ' . $model->branch_id;
$this->params['breadcrumbs'][] = ['label' => 'U2 Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->branch_id, 'url' => ['view', 'branch_id' => $model->branch_id, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="u2-branch-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
