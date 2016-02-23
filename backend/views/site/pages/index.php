<?php

use yii\web\View;

//use yii\helpers\Html;

/* @var $this View */

?>
<?= __FILE__ ?>
<?php
$sales = new backend\models\sales\Sales();
$sales->save(false);