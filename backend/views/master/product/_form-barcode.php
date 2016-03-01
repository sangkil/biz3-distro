<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<div id="bcode_record" class="pull-right" style="width: 40%; min-height: 180px; margin: 10px; padding: 10px; background-color: whitesmoke;">Update Logs:</div>
<table id="bcodeTable" class="table table-hover" style="width: 40%;">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th>Barcodes</th>
            <th style="text-align:center;">Action</th>
        </tr>
        <tr>
            <td>#</td>
            <td>
                <?= \yii\helpers\Html::textInput('tbarcode', '', ['class' => 'form-control tbarcode']) ?>
            </td>
            <td style="text-align:center;">
                <?= \yii\helpers\Html::a('<i class="fa fa-plus"></i>', '#', ['id' => 'bcode_add', 'class' => 'btn btn-default text-green']) ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $row = '';
        $i = 0;
        foreach ($product_bcode as $bcode) {
            $row .= '<tr class="rowBcode">';
            $row .= '<td class="bcode_no">' . ($i + 1) . '</td>';
            $row .= '<td class="bcode_barcode">';
            $row .= $bcode->barcode;
            $row .= '</td>';
            $row .= '<td style="text-align:center;">' .
                    \yii\helpers\Html::a('<i class="fa fa-minus"></i>', '#', ['class' => 'btn btn-default text-orange bcode_remove'])
                    . '</td>';
            $row .= \yii\helpers\Html::input('hidden', 'prodBcode[' . $i . '][barcode]', $bcode->barcode, ['class' => 'barcode']);
            $row .= '</tr>';
            $i++;
        }

        $row_template = '<tr class="bcode_template" style="display:none;">';
        $row_template .= '<td class="bcode_no"></td>';
        $row_template .= '<td class="bcode_barcode"></td>';
        $row_template .= '<td style="text-align:center;">' .
                \yii\helpers\Html::a('<i class="fa fa-minus"></i>', '#', ['class' => 'btn btn-default text-orange bcode_remove'])
                . '</td>';
        $row_template .= '</tr>';
        echo $row.$row_template;
        ?> 
    </tbody>
</table>

