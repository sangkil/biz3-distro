<?php

namespace backend\models\accounting;

use Yii;

/**
 * This is the model class for table "gl_detail".
 *
 * @property integer $id
 * @property integer $header_id
 * @property integer $coa_id
 * @property double $amount
 *
 * @property Coa $coa
 * @property GlHeader $header
 */
class GlDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'gl_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['coa_id', 'amount'], 'required'],
            [['header_id', 'coa_id'], 'integer'],
            [['amount', 'debit', 'credit'], 'number'],
            [['coa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['coa_id' => 'id']],
            [['header_id'], 'exist', 'skipOnError' => true, 'targetClass' => GlHeader::className(), 'targetAttribute' => ['header_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'header_id' => 'Header ID',
            'coa_id' => 'Coa ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoa() {
        return $this->hasOne(Coa::className(), ['id' => 'coa_id']);
    }

    public function setDebit($value) {
        if ($value > 0) {
            $this->amount = $value;
        }
    }

    public function getDebit() 
    {
        return ($this->amount >= 0) ? $this->amount : null;
    }

    public function setCredit($value) {
        if ($value > 0) {
            $this->amount = -1 * $value;
        }
    }

    public function getCredit() {
        return ($this->amount < 0) ? -1 * $this->amount : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeader() {
        return $this->hasOne(GlHeader::className(), ['id' => 'header_id']);
    }

}
