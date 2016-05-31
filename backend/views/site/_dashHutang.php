<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<!-- /.item -->
<?php if ($model->sisa > 0) { ?>
    <?php
    $d1 = new \DateTime($model->due_date);
    $d2 = new \DateTime(date('Y-m-d'));
    $remain = ($model->sisa > 0) ? $d1->diff($d2)->days : false;
    $label_color = 'label-default';
    $label_color = ($remain<=14)?'label-success':$label_color;
    $label_color = ($remain<=7)?'label-warning':$label_color;
    $label_color = ($remain<=0)?'label-danger':$label_color;
    ?>
    <li class="item" style="<?php if ($index > 10000) { ?>border-top: 1px whitesmoke solid;<?php } ?> padding: 10px;">
        <div class="product-info">
            <?= yii\helpers\Html::a($model->number, ['/accounting/invoice/view', 'id' => $model->id]) ?>
            <span class="label <?= $label_color ?> pull-right"><?= number_format($model->sisa, 0) ?> </span>
            <span class="product-description">
                <?= 'Supplier ' . $model->vendor->name . '; due date ' . $model->DueDate ?>
            </span>
            <span class="product-description">
                <?= 'Total Invoive ' . number_format($model->value,0) . '; paid ' . number_format($model->paid,0) ?>
            </span>
        </div>
    </li>
<?php } ?>
<!-- /.item -->