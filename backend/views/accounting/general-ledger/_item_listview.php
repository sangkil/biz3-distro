<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
?>
<tr><td colspan="4">
        <!--        <div style="border-bottom:1px solid whitesmoke;">-->
        <table class="table no-border" style="width: 100%;">
            <tr>
                <td colspan="2" style="width: 10%;"><?= $model->GlDate . '/' . Html::a($model->number, \yii\helpers\Url::to(['/accounting/general-ledger/view', 'id' => $model->id])) ?></td>
                <td colspan="3"><?php
                    $bgcolor = ($model->status == $model::STATUS_DRAFT) ? 'bg-yellow' : 'bg-green';
                    $bgcolor = ($model->status == $model::STATUS_CANCELED) ? 'bg-red' : $bgcolor;
                    echo Html::tag('span', $model->nmStatus, ['class' => "badge $bgcolor"]);
                    ?></td>
            </tr>
            <?php
            foreach ($model->glDetails as $ddetail) {
                $temp = '';
                $temp .= Html::beginTag('tr');
                $temp .= Html::tag('td', '&nbsp;', ['style' => 'width:10%']);
                $temp .= Html::tag('td', $ddetail->coa->code, ['style' => 'width:10%']);
                $temp .= Html::tag('td', $ddetail->coa->name);
                $temp .= Html::tag('td', $ddetail->debit, ['style' => 'width:15%']);
                $temp .= Html::tag('td', $ddetail->credit, ['style' => 'width:15%']);
                //$temp .= Html::tag('td', $ddetail->amount);
                $temp .= Html::endTag('tr');
                echo $temp;
            }
            ?>
            <tr>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
                <td colspan="3"><?= strtoupper($model->description) ?></td>
            </tr>
            <tr>
                <td >&nbsp;</td>
                <td colspan="4">
                    
                </td>
            </tr>
        </table>
        <!--        </div>-->
    </td>
</tr>
