<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title></title>
        <?php $this->head() ?>
    </head>
    <body class="sidebar-collapse">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <div class="content-wrapper" style="padding-top: 0px; background-color: white">
                <section class="content">
                    <?= $content ?>
                </section>
            </div>
            <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
