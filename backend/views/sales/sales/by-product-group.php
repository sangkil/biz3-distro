<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\sales\Sales;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\Sales */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales/product - Monthly';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="pull-right">
    <?=
    Html::a('<i class="fa fa-download"></i>', ['by-product-group-csv', 'SalesDtl[FrDate]' => $searchModel->FrDate, 'SalesDtl[ToDate]' => $searchModel->ToDate, 'SalesDtl[branch_id]' => $searchModel->branch_id], [
        'class' => 'btn btn-default', 'title' => 'CSV Download', 'target' => '_blank',
        'data' => [
            'method' => 'post',
        ],
    ])
    ?>
</p><br>
<div class="sales-dtl-index"> 
    <?php echo $this->render('_searchdtl', ['model' => $searchModel]); ?> 
    <div class="col-lg-6">
        <?php
        $content = Html::beginTag('table', ['class' => 'table']);
        $hdr = ['No', 'Group Name', 'Sales Value', 'Diskon','Sub Total'];
        $hdrw = [10, 25, 20,20, 35];
        $content .= Html::beginTag('tr');
        $j = 0;
        foreach ($hdr as $value) {
            $algn = (in_array($j, [2, 3, 4, 5])) ? 'right;' : 'left;';
            $content .= ($j == 3) ? Html::tag('th', $value) : Html::tag('th', $value, ['style' => 'width:' . $hdrw[$j] . '%; text-align:' . $algn]); //['style'=>'text-align:right;']
            $j++;
        }
        $content .= Html::endTag('tr');

        $dbfore = '';
        $is_first = true;
        $i = 0;
        $total['amount'] = 0;
        $total['disc'] = 0;
        foreach ($dataProvider->models as $row) {
            $content .= Html::beginTag('tr');
            $content .= Html::tag('td', ($i+1)); //category
            $content .= Html::tag('td', $row->group_name); //category
            $content .= Html::tag('td', number_format($row->amount, 0), ['style' => 'text-align:right;']);
            $content .= Html::tag('td', number_format($row->disc, 0), ['style' => 'text-align:right;']);
            $content .= Html::tag('td', number_format($row->amount - $row->disc, 0), ['style' => 'text-align:right;']);
            $content .= Html::endTag('tr');
            
            $total['amount'] += $row->amount;
            $total['disc'] += $row->disc;

            $dbfore = $row->SDate;
            $is_first = false;
            $i++;
        }
        
        $content .= Html::beginTag('tr');
        $content .= Html::tag('td', ''); //category
        $content .= Html::tag('td', 'Total'); //category
        $content .= Html::tag('td', '<b>'.number_format($total['amount'], 0).'</b>', ['style' => 'text-align:right;']);
        $content .= Html::tag('td', '<b>'.number_format($total['disc'], 0).'</b>', ['style' => 'text-align:right;']);
        $content .= Html::tag('td', '<b>'.number_format($total['amount'] - $total['disc'], 0).'</b>', ['style' => 'text-align:right;']);
        $content .= Html::endTag('tr');
            
        $content .= Html::endTag('table');
        echo $content;
        ?> 
    </div>
</div> 
