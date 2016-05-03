<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Product;
use backend\models\master\Warehouse;
use backend\models\master\Cogs;
/**
 * This is the model class for table "product_stock".
 *
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property integer $qty
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Product $product
 * @property Warehouse $warehouse
 */
class ProductStock extends \yii\db\ActiveRecord
{
    public $product_code;
    public $product_name;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'product_id', 'qty'], 'required'],
            [['warehouse_id', 'product_id', 'qty', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'warehouse_id' => 'Warehouse ID',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getCogs()
    {
        return $this->hasOne(Cogs::className(), ['product_id' => 'product_id']);
    }
    }
