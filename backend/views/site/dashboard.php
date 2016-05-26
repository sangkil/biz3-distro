<?php
/* @var $this yii\web\View */

use yii\widgets\ListView;

$this->title = 'SangkilBiz3-Distro';
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
