<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<!-- /.item -->
<?php //if($model->sisa>0){ ?>
<li class="item" style="<?php if ($index > 10000) { ?>border-top: 1px whitesmoke solid;<?php } ?> padding: 10px;">
    <div class="product-info">
        <label>
            <?= yii\helpers\Html::a($model->number, ['/inventory/transfer/view', 'id' => $model->id]) ?>
            <?= '/&nbsp;' . $model->Date ?>
        </label>        
<!--        <span class="label label-success pull-right"><?= ''//number_format($model->sisa,0)  ?> </span>-->
        <span class="product-description">
            <?= 'Source: ' . $model->branch->name ?>
            <?= 'Destination: ' . $model->branchDest->name . '<br>' ?>
            <?php
            foreach ($model->movements as $dmove) {
                echo ($dmove->status == backend\models\inventory\GoodsMovement::STATUS_CANCELED) ? '' : '&nbsp;' . \yii\helpers\Html::a($dmove->number, ['/inventory/gm-manual/view', 'id' => $dmove->id]);
            }
            ?>
        </span>
    </div>
</li>
<?php //}  ?>
<!-- /.item -->