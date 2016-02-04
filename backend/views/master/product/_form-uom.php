<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<div id="uom_record" class="pull-right" style="width: 40%; height: 180px; margin: 10px; padding: 10px; background-color: whitesmoke; overflow-y: scroll;">Update Logs:</div>
<table id="uomTable" class="table table-hover no-padding" style="width: 50%;">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 30%;">Uom Code</th>
            <th>Isi</th>
            <th style="text-align:center;">Action</th>
        </tr>
        <tr>
            <td>#</td>
            <td>
                <?= \yii\helpers\Html::dropDownList('id_uom', '', \backend\models\master\Uom::selectOptions(), ['prompt' => '-- Uoms --', 'class' => 'form-control uom']) ?>
            </td>
            <td>
                <?= \yii\helpers\Html::textInput('isi', '', ['class' => 'form-control isi']) ?>
            </td>
            <td style="text-align:center;">
                <?= \yii\helpers\Html::a('<i class="fa fa-plus"></i>', '#', ['class' => 'btn btn-default text-green', 'id' => 'uom_add']) ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $row = '';
        $i = 0;
        foreach ($product_uom as $roums) {
            $row .= '<tr class="rowUom">';
            $row .= '<td>' . ($i + 1) . '</td>';
            $row .= '<td>' . $roums->uom->name . '</td>';
            $row .= '<td>';
            $row .= $roums->isi;
            $row .= \yii\helpers\Html::input('hidden', 'prodUom['. $i .'][id_uom]', $roums->uom_id,['class'=>'id_uom']);
            $row .= \yii\helpers\Html::input('hidden', 'prodUom['. $i .'][isi]', $roums->isi,['class'=>'isi']);
            $row .= '</td>';
            $row .= '<td style="text-align:center;">' .
                    \yii\helpers\Html::a('<i class="fa fa-minus"></i>', '#', ['class' => 'btn btn-default text-orange uom_remove'])
                    . '</td>';
            $row .= '</tr>';
            $i++;
        }
        $row_template = '<tr class="uom_template" style="display:none;">';
        $row_template .= '<td class="uom_no"></td>';
        $row_template .= '<td class="uom_code"></td>';
        $row_template .= '<td class="uom_isi"></td>';
        $row_template .= '<td style="text-align:center;">' .
                \yii\helpers\Html::a('<i class="fa fa-minus"></i>', '#', ['class' => 'btn btn-default text-orange uom_remove'])
                . '</td>';
        $row_template .= '</tr>';
        echo $row . $row_template;
        ?> 
    </tbody>
</table>
<?php
$this->registerJsFile('@web/../views/master/product/product_control.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
