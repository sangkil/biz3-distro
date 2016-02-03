<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<div id="uom_record" class="pull-right" style="width: 40%; min-height: 180px; margin: 10px; padding: 10px; background-color: whitesmoke;">Update Logs:</div>
<table class="table table-hover no-padding" style="width: 50%;">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 30%;">Uom Code</th>
            <th>Isi</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>#</td>
            <td>
                <?= \yii\helpers\Html::dropDownList('id_uom', '', \backend\models\master\Uom::selectOptions(), ['prompt' => '-- Uoms --', 'class' => 'form-control']) ?>
            </td>
            <td>
                <?= \yii\helpers\Html::textInput('isi', '', ['class' => 'form-control']) ?>
            </td>
            <td style="text-align:center;">
                <?= \yii\helpers\Html::a('<i class="fa fa-plus"></i>', '#', ['class' => 'btn btn-default text-green']) ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $row = '';
        $i = 1;
        foreach ($product_uom as $roums) {
            $row .= '<tr>';
            $row .= '<td>' . $i . '</td>';
            $row .= '<td>' . $roums->uom->code . '</td>';
            $row .= '<td>' . $roums->isi . '</td>';
            $row .= '<td style="text-align:center;">' .
                    \yii\helpers\Html::a('<i class="fa fa-minus"></i>', '#', ['class' => 'btn btn-default text-orange'])
                    . '</td>';
            $row .= '</tr>';
            $i++;
        }
        echo $row;
        ?> 
    </tbody>
</table>