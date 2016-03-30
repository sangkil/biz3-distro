<?php

use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\GlTemplate;

JuiAsset::register($this);
$opts = json_encode([
    'tmplate_url' => Url::to(['journal-templates']),
    ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script-by-template.js'));

$detailFunc = function ($model, $key) {
    $result = Html::activeHiddenInput($model, "[$key]id", ['data-field' => 'id']);
    $result .= Html::tag('td', Html::tag('span', Html::getAttributeValue($model, "[$key]es[name]"), ['data-field' => 'name']));
    $result .= Html::tag('td', Html::activeTextInput($model, "[$key]amount", ['data-field' => 'amount', 'class' => 'form-control']));
    $result .= Html::tag('td', Html::tag('a', '<i class="fa fa-minus"></i>'), ['data-action' => 'delete']);
    return $result;
}
?>

<div class="col-lg-12 no-padding no-margin">
    <table class="table table-hover" id="table-templates">
        <thead>
            <tr>
                <th>Template</th>
                <th>Amount</th>
                <th style="width: 5%">#</th>
            </tr>
            <tr>
                <th><input type="hidden" id="inp-template-id">
                    <input class="form-control" id="inp-template"></th>
                <th><input class="form-control" id="inp-amount"></th>
                <th ><a class="btn btn-default text-green" id="add-template"><i class="fa fa-plus"></i></a></th>
            </tr>
        </thead>
        <?=
        TabularInput::widget([
            'id' => 'template-grid',
            'allModels' => $templates,
            'model' => GlTemplate::className(),
            'tag' => 'tbody',
            'itemOptions' => ['tag' => 'tr'],
            'itemView' => $detailFunc,
            'clientOptions' => [
            ]
        ])
        ?>
    </table>
</div>
