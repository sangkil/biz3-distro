<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<!-- /.item -->
<?php //if($model->sisa>0){ ?>
<li class="item" style="<?php if($index>10000){ ?>border-top: 1px whitesmoke solid;<?php } ?> padding: 10px;">
    <div class="product-info">
        <?= yii\helpers\Html::a($model->number, ['/inventory/transfer/view','id'=>$model->id]) ?>
        <span class="label label-success pull-right"><?= ''//number_format($model->sisa,0) ?> </span>
        <span class="product-description">
            <?= 'Source: '.$model->branch->name ?>
            <?= 'Destination: '.$model->branchDest->name ?>
        </span>
    </div>
</li>
<?php //} ?>
<!-- /.item -->