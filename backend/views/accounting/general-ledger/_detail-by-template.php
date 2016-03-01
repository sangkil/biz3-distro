<?php

use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;

JuiAsset::register($this);
$opts = json_encode([
    'tmplate_url' => Url::to(['journal-templates']),
        ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script-by-template.js'));
?>

<div class="col-lg-12 no-padding no-margin">
    <table class="table table-hover" id="table-templates">
        <?php
        $inputArea = Html::beginTag('thead');
        $inputArea .= Html::beginTag('tr');
        $inputArea .= Html::tag('th', 'Template');
        $inputArea .= Html::tag('th', 'Amount', ['style' => 'width:16%;']);
        $inputArea .= Html::tag('th', '#', ['style' => 'width:5%;']);
        $inputArea .= Html::endTag('tr');
        $inputArea .= Html::beginTag('tr');
        $inputArea .= Html::tag('td', Html::hiddenInput('did', '', ['id' => 'did']) . Html::textInput('dname', '', ['id' => 'dname', 'class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::textInput('damount', '', ['id' => 'damount', 'class' => 'form-control']));
        $inputArea .= Html::tag('td', Html::a('<i class="fa fa-plus"></i>', '#', ['id' => 'journal_add', 'class' => 'btn btn-default text-green']));
        $inputArea .= Html::endTag('tr');
        $inputArea .= Html::endTag('thead');
        echo $inputArea;
        ?>
        <tbody>
            <?php
            $displayArea = Html::beginTag('tr',['class'=>'row-template' , 'style'=>'display:none;']);
            $displayArea .= Html::tag('td', '', ['data-field' => 'iid']);
            $displayArea .= Html::tag('td', '', ['data-field'=>'iamount']);
            $displayArea .= Html::tag('td', Html::a('<i class="fa fa-minus"></i>', '#', ['id' => 'journal_min', 'class' => 'btn btn-minus btn-default text-red']));
            $displayArea .= Html::endTag('tr');     
            echo $displayArea;
            ?>
        </tbody>
    </table>
</div>
