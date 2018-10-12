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
    Html::a('<i class="fa fa-download"></i>', ['by-product-month-csv', 'SalesDtl[FrDate]' => $searchModel->FrDate, 'SalesDtl[ToDate]' => $searchModel->ToDate, 'SalesDtl[branch_id]' => $searchModel->branch_id], [
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
        $hdrw = [5, 20, 10, 10, 10, 10];
        $content .= Html::beginTag('tr');
        $j = 0;
        foreach ($hdr as $value) {
            $algn = (in_array($j, [1, 2, 3])) ? 'right;' : 'left;';
            $content .= ($j == 3) ? Html::tag('th', $value) : Html::tag('th', $value, ['style' => 'width:' . $hdrw[$j] . '%; text-align:' . $algn]); //['style'=>'text-align:right;']
            $j++;
        }
        $content .= Html::endTag('tr');

        $dbfore = '';
        $is_first = true;
        $i = 0;
        foreach ($dataProvider->models as $row) {
            $content .= Html::beginTag('tr');
            $content .= Html::tag('td', ($i+1)); //category
            $content .= Html::tag('td', $row->group_name); //category
            $content .= Html::tag('td', number_format($row->amount, 0), ['style' => 'text-align:right;']);
            $content .= Html::tag('td', number_format($row->disc, 0), ['style' => 'text-align:right;']);
            $content .= Html::tag('td', number_format($row->amount - $row->disc, 0), ['style' => 'text-align:right;']);
            $content .= Html::endTag('tr');

            $dbfore = $row->SDate;
            $is_first = false;
            $i++;
        }
        $content .= Html::endTag('table');
        echo $content;
        ?> 
    </div>
</div> 
