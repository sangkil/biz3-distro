<?php

namespace backend\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%stock_adjustment}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $warehouse_id
 * @property string $date
 * @property integer $reff_id
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property StockAdjustmentDtl[] $stockAdjustmentDtls
 */
class StockAdjustment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stock_adjustment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'warehouse_id', 'date', 'status'], 'required'],
            [['warehouse_id', 'reff_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['number'], 'string', 'max' => 16],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'warehouse_id' => 'Warehouse ID',
            'date' => 'Date',
            'reff_id' => 'Reff ID',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockAdjustmentDtls()
    {
        return $this->hasMany(StockAdjustmentDtl::className(), ['adjustment_id' => 'id']);
    }
}
