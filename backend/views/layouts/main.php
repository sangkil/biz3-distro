<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-red sidebar-mini sidebar-collapse">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="<?= Url::to(['/site/index']); ?>" class="logo"  style="border-bottom: whitesmoke solid 1px;">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>A</b>4</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>A4</b>Sport</span>
                </a>
            </header>
            <?php
            require 'left_menu.php';
            ?>
            <div class="content-wrapper" style="padding-top: 0px; background-color: white;">
                <section class="content-header" style="background-color: whitesmoke; height: 50px; border-bottom: red solid 1px;">
                    <h1> 
                        <a href="#" data-toggle="offcanvas" role="button">
                            <i class="fa fa-angle-left"></i>
                            <i class="fa fa-angle-right"></i>
                        </a>                        
                        <?= '&nbsp;' . $this->title ?>
                    </h1>
                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => ['label' => Yii::t('yii', 'Home'), 'url' => Yii::$app->homeUrl, 'template' => '<li><i class="fa fa-home"></i>&nbsp;{link}</li>'],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                </section>
                <section class="content" style="overflow-x: auto;">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </section>
            </div>

            <!--            <footer class="footer">
                            <div class="container">
                                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            
                                <p class="pull-right"><?= Yii::powered() ?></p>
                            </div>
                        </footer>-->

            <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
