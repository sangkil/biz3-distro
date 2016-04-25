<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
use backend\models\master\Branch;
use common\widgets\SideNav;

/* @var $this yii\web\View */
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar ">
        <!-- Sidebar user panel -->
        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="user-panel">
                <div class="pull-left image">
                    <?php
                    echo Html::img('@web/../../vendor/bower/adminlte/dist/img/user2-160x160.jpg', ['class' => 'img-circle',
                        'alt' => 'User Image']);
                    ?>
                </div>
                <div class="pull-left info">
                    <p><?= (Yii::$app->user->isGuest) ? 'Guest' : Yii::$app->user->identity->username ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <?= Html::beginForm(['/site/change-branch'], 'post', ['class'=>'sidebar-form'])?>
<!--                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
                    <span class="input-group-btn">
                        <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>-->
                    <?= Html::dropDownList('activeBrach', Yii::$app->profile->branch_id, Branch::selectAssignedOptions(), ['class' => 'form-control',
                        'prompt' => '== Active Branch ==', 'id' => 'select-branch']) ?>
            <?= Html::endForm()?>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php endif; ?>

        <?=
        SideNav::widget([
            'items' => require '_item_menu.php',
        ])
        ?>
    </section>
    <!-- /.sidebar -->
</aside>

