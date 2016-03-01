<?php

use mdm\widgets\TabularInput;
use backend\models\accounting\EntriSheetDtl;
use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;

JuiAsset::register($this);
$opts = json_encode([
    'coa_url' => Url::to(['list-coa']),
        ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="col-lg-12 no-padding no-margin">
    <table class="table table-hover">
        <?php
        $inputArea = Html::beginTag('thead');
        $inputArea .= Html::beginTag('tr');
        $inputArea .= Html::tag('th', 'Code', ['style' => 'width:20%;']);
        $inputArea .= Html::tag('th', 'Account Name');
        $inputArea .= Html::tag('th', 'D/K');
        $inputArea .= Html::tag('th', '#', ['style' => 'width:5%;']);
        $inputArea .= Html::endTag('tr');
        $inputArea .= Html::beginTag('tr');
        $inputArea .= Html::tag('td', Html::hiddenInput('did', '', ['id' => 'did']) . Html::textInput('dcode', '', ['id' => 'dcode', 'class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::textInput('dname', '', ['id' => 'dname', 'class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::dropDownList('dbalance', '', ['D' => 'Debit', 'K' => 'Kredit'], ['id' => 'dbalance', 'class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::a('<i class="fa fa-plus"></i>', '#', ['id' => 'journal_add', 'class' => 'btn btn-default text-green']));
        $inputArea .= Html::endTag('tr');
        $inputArea .= Html::endTag('thead');
        echo $inputArea;
        ?>
        <?=
        TabularInput::widget([
            'id' => 'detail-grid',
            'allModels' => $model->entriSheetDtls,
            'modelClass' => EntriSheetDtl::className(),
            'options' => ['tag' => 'tbody'],
            'itemOptions' => ['tag' => 'tr'],
            'itemView' => '_item_detail',
            'clientOptions' => []
        ])
        ?>
    </table>
</div>
