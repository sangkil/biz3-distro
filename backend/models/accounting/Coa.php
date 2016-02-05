<?php

namespace backend\models\accounting;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "coa".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $code
 * @property string $name
 * @property integer $type
 * @property string $normal_balance
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Coa $parent
 * @property Coa[] $coas
 * @property EntriSheetDtl[] $entriSheetDtls
 * @property GlDetail[] $glDetails
 */
class Coa extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'coa';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent_id', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code', 'name', 'type', 'normal_balance'], 'required'],
            [['code'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 64],
            [['normal_balance'], 'string', 'max' => 1],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'code' => 'Code',
            'name' => 'Name',
            'type' => 'Type',
            'normal_balance' => 'Normal Balance',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Coa::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoas() {
        return $this->hasMany(Coa::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntriSheetDtls() {
        return $this->hasMany(EntriSheetDtl::className(), ['coa_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlDetails() {
        return $this->hasMany(GlDetail::className(), ['coa_id' => 'id']);
    }

    public function behaviors() {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
