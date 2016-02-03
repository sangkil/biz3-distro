<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\inventory\GoodsMovement;
use backend\models\inventory\GoodsMovementDtl;
use yii\jui\AutoComplete;

/* @var $this View */
/* @var $model GoodsMovement */

$this->registerJs($this->render('_script.js'));
?>

<?= AutoComplete::widget([
    'clientOptions'=>[
        'source' => yii\helpers\Url::to(['list-product']),
    ],
    'options'=>[
        'id'=>'input-product'
    ]
]); ?>
<div class="col-lg-12">
    <div class="panel panel-info">        
        <table id="detail-grid" class="table table-striped">
            <?=
            TabularInput::widget([
                'id' => 'detail-grid',
                'allModels' => $model->items,
                'modelClass' => GoodsMovementDtl::className(),
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
