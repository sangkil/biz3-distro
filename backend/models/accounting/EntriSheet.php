<?php

namespace backend\models\accounting;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "entri_sheet".
 *
 * @property string $id
 * @property string $name
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property double $amount
 *
 * @property EntriSheetDtl[] $entriSheetDtls
 */
class EntriSheet extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;

    public $amount;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'entri_sheet';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['id','amount'], 'required'],
            [['id'], 'autonumber', 'format' => date('y') . '?', 'digit' => 4],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['id'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntriSheetDtls() {
        return $this->hasMany(EntriSheetDtl::className(), ['esheet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function setEntriSheetDtls($value) {
        $this->loadRelated('entriSheetDtls', $value);
    }
    
    
    public static function selectOptions() {
        return ArrayHelper::map(static::find()->asArray()->all(), 'id', 'name');
    }
    
    public function behaviors() {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
