<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\GlHeader */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Journal';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('New Journal', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="gl-header-index">
    <table class="table table-responsive">
        <thead>
            <tr>
                <th style="width: 10%;">Date/GLNumber</th>
                <th>Account & Description</th>
                <th style="width: 15%;">Debit</th>
                <th style="width: 15%;">Credit</th>
            </tr>
            <tr>
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </tr>
        </thead>
        <tbody>
            <?=
            ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'itemView' => '_item_listview',
            ]);
            ?>
        </tbody>
    </table>
    <?php
    /*
      GridView::widget([
      'dataProvider' => $dataProvider,
      'tableOptions' => ['class' => 'table table-hover'],
      'filterModel' => $searchModel,
      'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      //                    'id',
      'number',
      'description',
      'GlDate',
      // 'reff_type',
      // 'reff_id',
      'nmStatus',
      //'periode_id',
      //'branch_id',
      // 'created_at',
      // 'created_by',
      // 'updated_at',
      // 'updated_by',
      ['class' => 'yii\grid\ActionColumn'],
      ],
      ]);
     * 
     */
    ?>

</div>
