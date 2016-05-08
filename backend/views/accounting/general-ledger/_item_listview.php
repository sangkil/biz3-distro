<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
?>
<tr><td colspan="4">
        <table class="table no-border" style="width: 100%;">
            <tr>
                <td colspan="2" style="width: 10%;"><?= $model->GlDate . '/' . Html::a($model->number, \yii\helpers\Url::to(['/accounting/general-ledger/view',
        'id' => $model->id])) ?></td>
                <td colspan="3"></td>
            </tr>

            <?php
            ?>
            <?php
            $is_first = true;
            foreach ($model->glDetails as $ddetail) {
                $temp = '';
                $temp .= Html::beginTag('tr');
                if ($is_first) {
                    $bgcolor = ($model->status == $model::STATUS_DRAFT) ? 'bg-yellow' : 'bg-green';
                    $bgcolor = ($model->status == $model::STATUS_CANCELED) ? 'bg-red' : $bgcolor;
                    $temp .= Html::tag('td', Html::tag('span', $model->nmStatus, ['class' => "badge $bgcolor"]), ['style' => 'width:10%']);
                    $is_first = false;
                } else {
                    $temp .= Html::tag('td', '&nbsp;', ['style' => 'width:10%']);
                }

                $temp .= Html::tag('td', $ddetail->coa->code, ['style' => 'width:10%']);
                $temp .= Html::tag('td', $ddetail->coa->name);
                $temp .= Html::tag('td', ($ddetail->debit > 0) ? number_format($ddetail->debit) : '-', ['style' => 'width:15%;text-align:right;']);
                $temp .= Html::tag('td', ($ddetail->credit > 0) ? number_format($ddetail->credit) : '-', ['style' => 'width:15%;text-align:right;']);
                //$temp .= Html::tag('td', $ddetail->amount);
                $temp .= Html::endTag('tr');
                echo $temp;
            }
            ?>
            <tr>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
                <td colspan="3">
                    <?= Yii::$app->formatter->asReference($model->description) ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
