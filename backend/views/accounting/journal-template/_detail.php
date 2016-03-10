<?php

use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
JuiAsset::register($this);
$this->registerJsFile(Url::to(['master']));
$this->registerJs($this->render('_script.js'));
?>

<div class="col-lg-12 no-padding no-margin">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>D/K</th>
                <th style="width: 20%">Code</th>
                <th>Account Name</th>
            </tr>
        </thead>
        <tbody>
            <tr data-key="d">
                <td>Debit</td>
                <td ><?= Html::activeHiddenInput($model, 'd_coa_id', ['data-field' => 'id']) ?>
                    <?= Html::activeTextInput($model, 'dCoa[code]', ['class' => 'form-control auto-coa',
                        'data-field' => 'code']) ?>
                </td>
                <td><?= Html::activeTextInput($model, 'dCoa[name]', ['class' => 'form-control auto-coa', 'data-field' => 'name']) ?></td>
            </tr>
            <tr data-key="k">
                <td>Kredit</td>
                <td ><?= Html::activeHiddenInput($model, 'k_coa_id', ['data-field' => 'id']) ?>
<?= Html::activeTextInput($model, 'kCoa[code]', ['class' => 'form-control auto-coa', 'data-field' => 'code']) ?>
                </td>
                <td><?= Html::activeTextInput($model, 'kCoa[name]', ['class' => 'form-control auto-coa', 'data-field' => 'name']) ?></td>
            </tr>
        </tbody>
    </table>
</div>
