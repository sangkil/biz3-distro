<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<div id="bcode_record"  class="pull-right" style="width: 40%; min-height: 180px; margin: 10px; padding: 10px; background-color: whitesmoke;">Update Logs:</div>
<table class="table table-hover" style="width: 40%;">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th>Barcodes</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>#</td>
            <td>
                <?= \yii\helpers\Html::textInput('barcode', '', ['class' => 'form-control']) ?>
            </td>
            <td>
                <?= \yii\helpers\Html::a('<i class="fa fa-plus"></i>','#',['class'=>'btn btn-default text-green']) ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $row = '';
        $i = 1;
        foreach ($product_bcode as $bcode) {
            $row .= '<tr>';
            $row .= '<td>' . $i . '</td>';
            $row .= '<td>' . $bcode->barcode . '</td>';
             $row .= '<td>' .
                    \yii\helpers\Html::a('<i class="fa fa-minus"></i>', '#', ['class' => 'btn btn-default text-orange'])
                    . '</td>';
            
            $row .= '</tr>';
            $i++;
        }
        echo $row;
        ?> 
    </tbody>
</table>

