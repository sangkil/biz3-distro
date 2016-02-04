<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Invoice;
use backend\models\accounting\InvoiceDtl;
use yii\jui\JuiAsset;
use yii\helpers\Url;

/* @var $this View */
/* @var $model Invoice */

JuiAsset::register($this);
$opts = json_encode([
    'product_url' => Url::to(['list-product']),
    ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="col-lg-12">
    <input id="input-product">
</div>
<div class="col-lg-12">
    <div class="panel panel-info">
        <table class="table table-striped">
            <?=
            TabularInput::widget([
                'id' => 'detail-grid',
                'allModels' => $model->items,
                'modelClass' => InvoiceDtl::className(),
                'options' => ['tag' => 'tbody'],
                'itemOptions' => ['tag' => 'tr'],
                'itemView' => '_item_detail',
                'clientOptions' => [
                ]
            ])
            ?>
        </table>
    </div>
</div>
