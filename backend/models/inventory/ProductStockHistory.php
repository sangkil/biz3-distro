<?php

namespace app\models\inventory;

use Yii;
use backend\models\master\Product;
use backend\models\master\Warehouse;

/**
 * This is the model class for table "product_stock_history".
 *
 * @property double $time
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property double $qty_movement
 * @property double $qty_current
 * @property integer $movement_id
 */
class ProductStockHistory extends \yii\db\ActiveRecord
{
    public $product_code;
    public $product_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_stock_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'warehouse_id', 'product_id', 'qty_movement', 'qty_current'], 'required'],
            [['time', 'qty_movement', 'qty_current'], 'number'],
            [['warehouse_id', 'product_id', 'movement_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'time' => 'Time',
            'warehouse_id' => 'Warehouse ID',
            'product_id' => 'Product ID',
            'qty_movement' => 'Qty Movement',
            'qty_current' => 'Qty Current',
            'movement_id' => 'Movement ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }
}
