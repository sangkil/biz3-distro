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
 * @property integer $d_coa_id
 * @property integer $k_coa_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property double $amount
 *
 * @property Coa $dCoa
 * @property Coa $kCoa
 */
class EntriSheet extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait;

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
            [['d_coa_id','k_coa_id'], 'required'],
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
    public function getDCoa() {
        return $this->hasOne(Coa::className(), ['id' => 'd_coa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKCoa() {
        return $this->hasOne(Coa::className(), ['id' => 'k_coa_id']);
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
