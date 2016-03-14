<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use mdm\widgets\TabularInput;
use backend\models\accounting\GlDetail;
use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;

JuiAsset::register($this);
$opts = json_encode([
    'gl_url' => Url::to(['list-coa']),
    ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script_journal.js'));
?>

<table class="table table-hover">
    <?php
    $inputArea = Html::beginTag('thead');
    $inputArea .= Html::beginTag('tr');
    $inputArea .= Html::tag('th', 'Code', ['style' => 'width:20%;']);
    $inputArea .= Html::tag('th', 'Account Name');
    $inputArea .= Html::tag('th', 'Debit', ['style' => 'width:16%;']);
    $inputArea .= Html::tag('th', 'Credit', ['style' => 'width:16%;']);
    $inputArea .= ($model_journal->isNewRecord)? Html::tag('th', '#', ['style' => 'width:5%;']):'';
    $inputArea .= Html::endTag('tr');
    if ($model_journal->isNewRecord) {
        $inputArea .= Html::beginTag('tr');
        $inputArea .= '<td colspan="2">
                <div class="input-group" style="width: 100%; z-index: ">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default" id="selected_type"><i class="fa fa-search"></i></button>-->
                    </div>
                    <input id="dname" data-field="item_search" class="form-control" placeholder="Search Account..">
                </div>
            </td>';
        $inputArea .= Html::endTag('tr');
    }
    $inputArea .= Html::endTag('thead');
    echo $inputArea;
    ?>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid-journal',
        'allModels' => $model_journal->glDetails,
        'modelClass' => GlDetail::className(),
        'options' => ['tag' => 'tbody'],
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail_journal',
        'clientOptions' => []
    ])
    ?>
</table>
