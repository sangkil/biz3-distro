<?php

namespace backend\models\sales;

use Yii;
use backend\models\master\Product;
use backend\models\master\Uom;
use backend\models\master\Cogs;
use backend\models\master\ProductUom;

/**
 * This is the model class for table "sales_dtl".
 *
 * @property integer $sales_id
 * @property integer $product_id
 * @property integer $uom_id
 * @property double $qty
 * @property double $price
 * @property double $cogs
 * @property double $discount
 * @property double $total_release
 *
 * @property Sales $sales
 * @property Product $product
 */
class SalesDtl extends \yii\db\ActiveRecord {

    public $total_release;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%sales_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'uom_id', 'qty', 'price'], 'required'],
            [['sales_id', 'product_id', 'uom_id'], 'integer'],
            [['cogs'], 'default', 'value' => function() {
            $cogs = Cogs::findOne($this->product_id);
            return $cogs ? $cogs->cogs : null;
        }],
            [['cogs'], 'required'],
            [['qty', 'price', 'cogs', 'discount', 'total_release'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sales_id' => 'Sales ID',
            'product_id' => 'Product ID',
            'uom_id' => 'Uom ID',
            'qty' => 'Qty',
            'price' => 'Price',
            'discount' => 'Discount',
            'total_release' => 'Total Release',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales() {
        return $this->hasOne(Sales::className(), ['id' => 'sales_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUom() {
        return $this->hasOne(Uom::className(), ['id' => 'uom_id']);
    }

    /**
     * @return Double
     */
    public function getTotalLine() {
        $post_discount = (1-($this->discount/100));
        return ($this->qty * $this->price) * $post_discount;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductUom()
    {
        return $this->hasOne(ProductUom::className(), ['product_id' => 'product_id', 'uom_id' => 'uom_id']);
    }
}
