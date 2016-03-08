<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

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

    <body class="skin-yellow sidebar-mini sidebar-collapse">
        <?php $this->beginBody() ?>
        <div class="wrapper"> 
            <?php
            require 'left_menu.php';
            ?>
            <div class="content-wrapper" style="padding-top: 0px; background-color: white">
                <section class="content-header" >
                    <h1> 
                        <a href="#" data-toggle="offcanvas" role="button">
                            <i class="fa fa-angle-left"></i>
                            <i class="fa fa-angle-right"></i>
                        </a>                        
                        <?= '&nbsp;'.$this->title ?>
                    </h1>
                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => ['label' => Yii::t('yii', 'Home'),'url' => Yii::$app->homeUrl,'template'=>'<li><i class="fa fa-home"></i>&nbsp;{link}</li>'],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                </section>

                <section class="content">
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
