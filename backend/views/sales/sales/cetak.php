<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Sales */

$this->title = 'Cetak Faktur: '.$model->number;
?>
<div class="sales-view col-lg-3" style="background-color: white; page-break-after: true;">
    <p class="text-center">FAKTUR PENJUALAN<br><?= $model->number ?></p>
        <?php
        $totaLine = 0;
        $totalDiskon = 0;
        $dtPro = new yii\data\ActiveDataProvider([
            'query' => $model->getItems()->with(['product', 'uom']),
            'pagination' => false,
            'sort' => false,
        ]);
        if (!empty($dtPro->getModels())) {
            echo Html::beginTag('table',['style'=>'width:100%;']);
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
                echo Html::tag('td', $val->qty . $val->uom->code,['style'=>'height:2px; padding-right:5px;']);
                echo Html::tag('td', $dname);
                echo Html::tag('td', number_format($val->price * $val->qty * $val->productUom->isi, 0), ['style' => 'text-align:right;']);
                echo Html::endTag('tr');
                if ($val->discount > 0) {
                    echo Html::beginTag('tr');
                    echo Html::tag('td', '&nbsp;',['style'=>'height:2px;']);
                    echo Html::tag('td', "diskon $val->discount %",['style'=>'height:2px;']);
                    echo Html::tag('td', number_format($diskon * -1,0), ['style' => 'text-align:right;height:2px;']);
                    echo Html::endTag('tr');
                }
            }
            echo Html::beginTag('tr');
            echo Html::tag('td', '&nbsp;');
            echo Html::tag('td', 'Total');
            echo Html::tag('td', number_format($totaLine, 0), ['class'=>'text-bold', 'style' => 'text-align:right; border-top: #000 solid 1px;']);
            echo Html::endTag('tr');
            echo Html::endTag('table');
            echo 'Kasir: '.$model->kasir->username;
            echo '/' . date('dmy-H:i:s', $model->created_at);
            echo '<br>Terima Kasih';
        }
        ?>

</div>
<script type="text/javascript">
    print();
    window.close();
</script>


