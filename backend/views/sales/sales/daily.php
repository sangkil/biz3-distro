<?php

use yii\helpers\Html;
use yii\grid\GridView;
use scotthuangzl\googlechart\GoogleChart;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\Sales */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daily Sales';
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
                    'format' => 'date',
                    'filter' => Html::dropDownList('Sales[Date]', $dmonth, $bln, ['class' => 'form-control'])
                ],
                [
                    'attribute' => 'value',
                    'format' => ['decimal', 0],
                    'filter' => false
                ]
            ]
        ]);
        ?>
    </div>
    <div class="col-lg-6">
        <?php
        $data = [];
        $is_first = true;
        foreach ($dataProvider->models as $row) {
            if ($is_first) {
                $data = array_merge($data, array(['Day', 'Sales Valuse']));
                $is_first = false;
            }
            $data = array_merge($data, array(['\'' . $row->date . '\'', $row->value]));
        }

        if (count($data) > 0) {            
//            echo GoogleChart::widget(array('visualization' => 'PieChart',
//                'data' => $data,
//                'options' => array('title' => 'My Daily Sales')));

            echo GoogleChart::widget(array('visualization' => 'ColumnChart',
                'data' => $data,
                'options' => array('title' => 'My Daily Sales')));
        }
        ?>
    </div>
</div>
