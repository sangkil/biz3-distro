<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Sales */

$this->title = 'Cetak Faktur: ' . $model->number;
?>
<div class="sales-view col-lg-3" style="background-color: white; page-break-after: true;">
    <p class="text-center">
        <label><?= $model->branch->name ?></label>
        <br><?= $model->branch->addr ?>
    </p>
    <p class="text-left" style="margin-bottom: 0px;">
        No.Faktur:<?= '&nbsp;'.$model->number ?><br>
        Tgl:<?= '&nbsp;'.date('d-m-Y H:i:s', $model->created_at) ?>&nbsp;
        Kasir:<?= '&nbsp;'.$model->kasir->username ?>
    </p>    
    <?php
    $totaLine = 0;
    $totalDiskon = 0;
    $dtPro = new yii\data\ActiveDataProvider([
        'query' => $model->getItems()->with(['product', 'uom']),
        'pagination' => false,
        'sort' => false,
    ]);
    if (!empty($dtPro->getModels())) {
        echo Html::beginTag('table', ['style' => 'width:100%; border-top:1px dotted #000;']);
        foreach ($dtPro->getModels() as $key => $val) {
            $b4diskon = $val->price * $val->qty * $val->productUom->isi;
            $afdiskon = $b4diskon * (1 - $val->discount / 100);
            $diskon = $b4diskon - $afdiskon;

            $totaLine += $afdiskon;
            $totalDiskon += $b4diskon - $afdiskon;

            $split = explode(';', $val->product->name);
            $newname = $split[1] . ';' . $split[2] . ';' . $split[0];
            $dname = (strlen($newname) > 21) ? substr($newname, 0, 21) : $newname;

            echo Html::beginTag('tr');
            echo Html::tag('td', $val->qty . $val->uom->code, ['style' => 'height:2px; padding-right:5px;']);
            echo Html::tag('td', $dname);
            echo Html::tag('td', number_format($val->price * $val->qty * $val->productUom->isi, 0), ['style' => 'text-align:right;']);
            echo Html::endTag('tr');
            if ($val->discount > 0) {
                echo Html::beginTag('tr');
                echo Html::tag('td', '&nbsp;', ['style' => 'height:2px;']);
                echo Html::tag('td', "diskon $val->discount %", ['style' => 'height:2px;']);
                echo Html::tag('td', number_format($diskon * -1, 0), ['style' => 'text-align:right;height:2px;']);
                echo Html::endTag('tr');
            }
        }
        echo Html::beginTag('tr', ['style' => 'border-top:1px dotted #000;']);
        echo Html::tag('td', '&nbsp;');
        echo Html::tag('td', 'Total', ['style' => 'text-align:right;']);
        echo Html::tag('td', number_format($totaLine, 0), ['class' => 'text-bold', 'style' => 'text-align:right;']);
        echo Html::endTag('tr');

        $isfirst = true;
        foreach ($model->payments as $dpay) {
            if ($isfirst) {
                echo Html::beginTag('tr',['style' => 'height:30px;']);
                echo Html::tag('td', '&nbsp;');
                echo Html::tag('td', 'DiBayar:', ['style' => 'text-align:right;']);
                echo Html::tag('td', '&nbsp;');
                echo Html::endTag('tr');
                $isfirst = false;
            }
            echo Html::beginTag('tr');
            echo Html::tag('td', '&nbsp;');
            echo Html::tag('td', $dpay->payment->paymentMethod->method, ['style' => 'text-align:right;']);
            echo Html::tag('td', number_format($dpay->value, 0), ['style' => 'text-align:right;']);
            echo Html::endTag('tr');
        }

        echo Html::endTag('table');
        echo '<br>Terima Kasih';
        echo '<br>Barang yang telah dibeli, tidak dapat dikembalikan';
    }
    ?>

</div>
<script type="text/javascript">
    print();
    window.close();
</script>


