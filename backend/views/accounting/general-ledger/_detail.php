<?php

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
$this->registerJs($this->render('_script.js'));
?>

<div class="col-lg-12 no-padding no-margin">
    <table class="table table-hover">
        <?php
        $inputArea = Html::beginTag('thead');
        $inputArea .= Html::beginTag('tr');
        $inputArea .= Html::tag('th', 'Code', ['style' => 'width:20%;']);
        $inputArea .= Html::tag('th', 'Account Name');
        $inputArea .= Html::tag('th', 'Debit', ['style' => 'width:16%; background-color:#FAFAFA; border-right:2px solid white;']);
        $inputArea .= Html::tag('th', 'Credit', ['style' => 'width:16%;background-color:#FAFAFA']);
        $inputArea .= Html::tag('th', '#', ['style' => 'width:5%;']);
        $inputArea .= Html::endTag('tr');
        $inputArea .= Html::beginTag('tr');
        $inputArea .= Html::tag('td', Html::hiddenInput('did', '', ['id'=>'did']). Html::textInput('dcode', '', ['id'=>'dcode', 'class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::hiddenInput('dbalance', '', ['id'=>'dbalance']).Html::textInput('dname', '', ['id'=>'dname','class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::input('number','debit', '', ['id'=>'ddebit','class' => 'form-control']),['style' => 'background-color:#FAFAFA; border-right:2px solid white;']);
        $inputArea .= Html::tag('td', Html::input('number','credit', '', ['id'=>'dcredit','class' => 'form-control']),['style' => 'background-color:#FAFAFA;']);
        $inputArea .= Html::tag('td', Html::a('<i class="fa fa-plus"></i>', '#', ['id' => 'journal_add', 'class' => 'btn btn-default text-green']));
        $inputArea .= Html::endTag('tr');
        $inputArea .= Html::endTag('thead');
        echo $inputArea;
        ?>
        <?=
        TabularInput::widget([
            'id' => 'detail-grid',
            'allModels' => $model->glDetails,
            'modelClass' => GlDetail::className(),
            'options' => ['tag' => 'tbody'],
            'itemOptions' => ['tag' => 'tr'],
            'itemView' => '_item_detail',
            'clientOptions' => [
            ]
        ])
        ?>
    </table>
</div>
