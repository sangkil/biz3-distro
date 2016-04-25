<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Branch */

$this->title = 'Create U2 Branch';
$this->params['breadcrumbs'][] = ['label' => 'U2 Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row u2-branch-create">
    <div class="col-lg-5">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>
</div>
