<?php

use yii\helpers\Html;
use yii\grid\GridView;
use scotthuangzl\googlechart\GoogleChart;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\Sales */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $dbranch.'Daily Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Sales', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="row sales-index">

    <?php
    // echo $this->render('_search', ['model' => $searchModel]); 
    $totaLine = 0;
    foreach ($dataProvider->getModels() as $key => $val) {
        $totaLine += $val->value;
    }
    ?>
    <div class="col-lg-4">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover'],
            'filterModel' => $searchModel,
            'showFooter' => true,
            'columns' => [
                [
                    'attribute' => 'date',
                    'format' => 'html',
                    'value'=>function($model)use($searchModel){
                        return Html::a($model->Date, ['/sales/sales/index','Sales[date]'=>$model->date, 'Sales[branch_id]'=>$searchModel->branch_id]);
                    },
                    'filter' => Html::dropDownList('Sales[Date]', $dmonth, $bln, ['class' => 'form-control'])
                ],
                [
                    'attribute' => 'value',
                    'format' => ['decimal', 0],
                    'headerOptions' => ['style' => 'text-align:right; width:20%;'],
                    'contentOptions' => ['style' => 'text-align:right;'],
                    'footerOptions' => ['style' => 'text-align:right;', 'class' => 'text-bold'],
                    'footer' => number_format($totaLine, 0),
                    'filter' => false
                ],
            ]
        ]);
        ?>
    </div>
    <div class="col-lg-8">
        <?php
        $data = [];
        $is_first = true;
        $kum = 0;
        foreach ($dataProvider->models as $row) {
            if ($is_first) {
                $data = array_merge($data, array(['Day', 'Value', 'Kumulatif']));
                $is_first = false;
            }
            $kum += $row->value;
            $data = array_merge($data, array([$row->Date, $row->value, $kum]));
        }

        if (count($data) > 0) {
            echo GoogleChart::widget([
                'visualization' => 'ComboChart',
                'data' => $data,
                'htmlOptions'=>['style'=>'height:650px;'],
                'options' => [
                    'title' => 'My Daily Sales',
                    'vAxis' => ['title' => 'Sales'],
                    'hAxis' => ['title' => 'Date'],
                    'seriesType' => 'bars',
                    'series' => [1 => ['type' => 'line']]
                ]
            ]);
        }
        ?>
    </div>
</div>
