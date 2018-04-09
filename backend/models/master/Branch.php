<?php

namespace backend\models\master;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property integer $orgn_id
 * @property string $code
 * @property string $name
 * @property string $addr
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Orgn $orgn
 * @property UserToBranch[] $userToBranches
 * @property Warehouse[] $warehouses
 */
class Branch extends \yii\db\ActiveRecord
{

    const BRANCH_OLSHOP = 6;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orgn_id', 'code', 'name'], 'required'],
            [['orgn_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code'], 'string', 'max' => 4],
            [['name'], 'string', 'max' => 32],
            [['addr'], 'string', 'max' => 128],
            [['orgn_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orgn::className(), 'targetAttribute' => ['orgn_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orgn_id' => 'Orgn ID',
            'code' => 'Code',
            'name' => 'Name',
            'addr' => 'Address',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrgn()
    {
        return $this->hasOne(Orgn::className(), ['id' => 'orgn_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserToBranches()
    {
        return $this->hasMany(UserToBranch::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['branch_id' => 'id']);
    }

    public static function find()
    {
        return new BranchQuery(get_called_class());
    }

    public static function selectOptions()
    {
        return ArrayHelper::map(static::find()->asArray()->all(), 'id', 'name');
    }
    
    public static function selectAssignedOptions()
    {
        return ArrayHelper::map(static::find()->asArray()->assigned()->all(), 'id', 'name');
    }

    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }
}
