<?php

namespace backend\models\accounting;

use Yii;

/**
 * This is the model class for table "entri_sheet_dtl".
 *
 * @property string $esheet_id
 * @property string $id
 * @property string $name
 * @property integer $coa_id
 * @property string $dk
 *
 * @property Coa $coa
 * @property EntriSheet $esheet
 */
class EntriSheetDtl extends \yii\db\ActiveRecord {

    const DK_DEBIT = 'D';
    const DK_CREDIT = 'K';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'entri_sheet_dtl';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['coa_id', 'dk'], 'required'],
            [['id'], 'autonumber', 'format' => date('y') . '?', 'digit' => 4],
            [['coa_id'], 'integer'],
            [['esheet_id', 'id'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 64],
            [['dk'], 'string', 'max' => 1],
            [['coa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['coa_id' => 'id']],
            [['esheet_id'], 'exist', 'skipOnError' => true, 'targetClass' => EntriSheet::className(), 'targetAttribute' => ['esheet_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'esheet_id' => 'Esheet ID',
            'id' => 'ID',
            'name' => 'Name',
            'coa_id' => 'Coa ID',
            'dk' => 'Dk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoa() {
        return $this->hasOne(Coa::className(), ['id' => 'coa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsheet() {
        return $this->hasOne(EntriSheet::className(), ['id' => 'esheet_id']);
    }

}
