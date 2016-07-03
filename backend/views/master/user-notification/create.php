<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\UserNotification */

$this->title = 'Create User Notification';
$this->params['breadcrumbs'][] = ['label' => 'User Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-notification-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
