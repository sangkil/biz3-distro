<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\UserNotification */

$this->title = 'Update User Notification: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-notification-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
