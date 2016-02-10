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
                <td colspan="3">&nbsp;</td>
            </tr>
            <?php
            foreach ($model->glDetails as $ddetail) {
                $temp = '';
                $temp .= Html::beginTag('tr');
                $temp .= Html::tag('td', '&nbsp;', ['style' => 'width:10%']);
                $temp .= Html::tag('td', $ddetail->coa->code . '-' . $ddetail->coa->name, ['style' => 'colspan:2;']);
                $temp .= Html::tag('td', $ddetail->debit, ['style' => 'width:15%']);
                $temp .= Html::tag('td', $ddetail->credit, ['style' => 'width:15%']);
                //$temp .= Html::tag('td', $ddetail->amount);
                $temp .= Html::endTag('tr');
                echo $temp;
            }
            ?>
            <tr>
                <td style="width:10%;">&nbsp;</td>
                <td colspan="4"><?= strtoupper($model->description) ?></td>
            </tr>
            <tr>
                <td style="width:10%;">&nbsp;</td>
                <td colspan="4">
                    <?php
                    $bgcolor = ($model->status == $model::STATUS_DRAFT) ? 'bg-yellow' : 'bg-green';
                    echo Html::tag('span', $model->nmStatus, ['class' => "badge $bgcolor"]);
                    ?>
                </td>
            </tr>
        </table>
        <!--        </div>-->
    </td>
</tr>
