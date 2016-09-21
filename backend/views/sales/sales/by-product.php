<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\sales\Sales;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\Sales */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales/product - Weekly';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="pull-right">
    <?=
    Html::a('<i class="fa fa-download"></i>', ['by-product-csv', 'SalesDtl[FrDate]' => $searchModel->FrDate, 'SalesDtl[ToDate]' => $searchModel->ToDate], [
        'class' => 'btn btn-default', 'title' => 'CSV Download', 'target' => '_blank',
        'data' => [
            'method' => 'post',
        ],
    ])
    ?>
</p><br>
<div class="sales-dtl-index"> 
    <?php echo $this->render('_searchdtl', ['model' => $searchModel]); ?> 
    <?php
    $content = Html::beginTag('table', ['class' => 'table']);
    $hdr = ['HARI/TANGGAL', 'NO.BON', 'ARTIKEL', 'NAMA BARANG', 'KATEGORI', 'SIZE', 'QTY', 'HARGA'];
    $hdrw = [5, 10, 10, 0, 10, 10, 5, 15];
    $content .= Html::beginTag('tr');
    $j = 0;
    foreach ($hdr as $value) {
        $algn = ($j == 7) ? 'right;' : 'left;';
        $content .= ($j == 3) ? Html::tag('th', $value) : Html::tag('th', $value, ['style' => 'width:' . $hdrw[$j] . '%; text-align:' . $algn]); //['style'=>'text-align:right;']
        $j++;
    }
    $content .= Html::endTag('tr');

    $dbfore = '';
    $is_first = true;
    $i = 0;
    $tot = 0;
    $tot_jend = 0;
    foreach ($dataProvider->models as $row) {
        if (!$is_first && $row->SDate != $dbfore) {
            $content .= Html::beginTag('tr');
            $content .= Html::tag('td', '&nbsp;', ['colspan' => count($hdr) - 1]);
            $content .= Html::tag('th', number_format($tot, 0), ['style' => 'text-align:right;']);
            $content .= Html::endTag('tr');

            if ($tot_jend > $tot) {
                $content .= Html::beginTag('tr');
                $content .= Html::tag('td', '&nbsp;', ['colspan' => count($hdr) - 1]);
                $content .= Html::tag('th', number_format($tot_jend, 0), ['style' => 'text-align:right;']);
                $content .= Html::endTag('tr');
            }

            $content .= Html::endTag('table');
            $content .= Html::tag('br');
            $content .= Html::beginTag('table', ['class' => 'table']);
            $j = 0;

            foreach ($hdr as $value) {
                $algn = ($j == 7) ? 'right;' : 'left;';
                $content .= ($j == 3) ? Html::tag('th', $value) : Html::tag('th', $value, ['style' => 'width:' . $hdrw[$j] . '%; text-align:' . $algn]); //['style'=>'text-align:right;']
                $j++;
            }
            $tot = ($row->price * $row->qty);
        } else {
            $tot += ($row->price * $row->qty);
        }
        $tot_jend += ($row->price * $row->qty);

        $content .= Html::beginTag('tr');
        $tanggal = $row->sdate;
        $day = date('D', strtotime($tanggal));

        $content .= Html::tag('td', ($row->SDate == $dbfore) ? '' : $days[$day] . ', ' . $row->SDate); //tgl
        $content .= Html::tag('td', $row->faktur); //bon
        $prod = explode(';', $row->pname);
        $content .= Html::tag('td', (isset($prod[1])) ? $prod[1] : ''); //artikel
        $content .= Html::tag('td', (isset($prod[0])) ? $prod[0] : ''); //product name
        $content .= Html::tag('td', (isset($row->ctgr)) ? $cats[$row->ctgr][1] : ''); //category
        $content .= Html::tag('td', (isset($prod[2])) ? $prod[2] : '', ['style' => 'text-align:center;']); //size
        $content .= Html::tag('td', $row->qty);
        $content .= Html::tag('td', number_format(($row->price * $row->qty), 0), ['style' => 'text-align:right;']);
        $content .= Html::endTag('tr');

        $dbfore = $row->SDate;
        $is_first = false;
        $i++;
    }
    $content .= Html::beginTag('tr');
    $content .= Html::tag('td', '&nbsp;', ['colspan' => count($hdr) - 1]);
    $content .= Html::tag('th', number_format($tot, 0), ['style' => 'text-align:right;']);
    $content .= Html::endTag('tr');
    if ($tot_jend > $tot) {
        $content .= Html::beginTag('tr');
        $content .= Html::tag('td', '&nbsp;', ['colspan' => count($hdr) - 1]);
        $content .= Html::tag('th', number_format($tot_jend, 0), ['style' => 'text-align:right;']);
        $content .= Html::endTag('tr');
    }
    $content .= Html::endTag('table');
    echo $content;
    ?> 
</div> 
