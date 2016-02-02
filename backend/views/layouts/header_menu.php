<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

if (!empty(Yii::$app->session->get('user.div')) && !empty(Yii::$app->session->get('user.div.active'))) {
    $u2b = Yii::$app->session->get('user.div');
    $u2b_active = Yii::$app->session->get('user.div.active');
}
?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?= Url::to(['/site/index']); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>AES</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>AWS</b>&nbsp;Enterprise&nbsp;Syst</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">                
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
<!--                        <img src="<?= ''//$theme . '/dist/img/user2-160x160.jpg'                   ?>" class="user-image" alt="User Image"/>-->
                        <i class="fa fa-sitemap"></i>
                        <span class="hidden-xs"><?= (Yii::$app->user->isGuest) ? 'Anonimous' : strtoupper(Yii::$app->user->identity->username).' - ' ?><?= (!empty($u2b_active)) ? $u2b_active['idBranch']['nm_branch'] : ''; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php echo Html::img('@web/../vendor/bower/adminlte/dist/img/user2-160x160.jpg', ['class' => 'img-circle']) ?>
                            <p>
                                <?= (Yii::$app->user->isGuest) ? 'Not Login' : Yii::$app->user->identity->username ?> - Role/div
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!--
                        <li class="user-body">
                            <div class="col-lg-8">
                            </div>   
                        </li>
                        -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <?php
                            $form = ActiveForm::begin([
                                        'id' => 'chg-branch',
                                        'options' => ['class' => 'form-horizontal'],
                                        'fieldConfig' => [
                                            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                                            'labelOptions' => ['class' => 'col-lg-1 control-label'],
                                        ],
                            ]);
                            ?>
                            <?php
                            $items = (!empty($u2b)) ? ArrayHelper::map($u2b, 'id_branch', 'idBranch.nm_branch') : [];
                            $bactive = (!empty($u2b_active)) ? $u2b_active['id_branch'] : -1;
                            echo (!empty($items)) ? Html::dropDownList('active_prince', $bactive, $items, ['class' => 'pull-left form-control', 'style' => 'width:60%;',
                                        'onchange' => 'this.form.submit()']) : '';
                            ?>
                            <?php ActiveForm::end(); ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['/site/logout']); ?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

