<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>
<div class="user-view">
    <div class="pull-right">
        <p>
            <?php
            if (trim($model->password_reset_token) == '' || $model->password_reset_token == null) {
                echo Html::a(Yii::t('rbac-admin', 'Request Reset Pwd'), ['request-password-reset', 'id' => $model->id, 'email' => $model->email], [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'method' => 'post'
                    ],
                ]);
            }else{
                echo Html::a(Yii::t('rbac-admin', 'Reset Pwd'), ['reset-password', 'token' => $model->password_reset_token], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post'
                    ],
                ]);
            }
            ?>
            <?php
            if ($model->status == 0 && Helper::checkRoute($controllerId . 'activate')) {
                echo Html::a(Yii::t('rbac-admin', 'Activate'), ['activate', 'id' => $model->id], [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
            <?php
            if (Helper::checkRoute($controllerId . 'delete')) {
                echo Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
        </p>
    </div>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            'created_at:date',
            'status',
        ],
    ])
    ?>

</div>
