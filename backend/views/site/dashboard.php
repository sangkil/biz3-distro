<?php
/* @var $this yii\web\View */

use yii\widgets\ListView;

$this->title = 'Dashboard '. $mperiode;
?>
<div class="row site-index">
<?=
ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}",
    'itemView' => '_dashSales',
]);
?>
</div>
