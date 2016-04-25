<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\widgets\DetailView;
?>
<div class="gl-header-form">
    <?php
        if(!$model_journal->isNewRecord){
            echo DetailView::widget([
                    'model' => $model_journal,
                    'options' => ['class' => 'table'],
                    'template' => '<tr><th style="width:15%;">{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        [
                            'label'=>'GL Number',
                            'attribute'=>'hyperlink',
                            'format'=>'raw'
                        ],
                        [
                            'label'=>'Posting Date',
                            'attribute'=>'GlDate'
                        ]
//                        'created_by',
//                        'created_by',
//                        'created_at:datetime',
//                        'updated_by',
//                        'updated_at:datetime',
                    ],
                ]);
        }
    ?>
    <?= $this->render('_detail_journal', ['model_journal' => $model_journal, 'form' => $form]) ?>
</div>
