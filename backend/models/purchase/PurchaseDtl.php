<?php

namespace backend\models\purchase;

use Yii;
use backend\models\master\Product;
use backend\models\master\Uom;

/**
 * This is the model class for table "purchase_dtl".
 *
 * @property integer $purchase_id
 * @property integer $product_id
 * @property integer $uom_id
 * @property double $qty
 * @property double $price
 * @property double $discount
 * @property double $total_receive
 *
 * @property Purchase $purchase
 */
class PurchaseDtl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%purchase_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'uom_id', 'qty', 'price'], 'required'],
            [['purchase_id', 'product_id', 'uom_id'], 'integer'],
            [['qty', 'price', 'discount', 'total_receive'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_id' => 'Purchase ID',
            'product_id' => 'Product ID',
            'uom_id' => 'Uom ID',
            'qty' => 'Qty',
            'price' => 'Price',
            'discount' => 'Discount',
            'total_receive' => 'Total Receive',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
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
    public function getUom()
    {
        return $this->hasOne(Uom::className(), ['id' => 'uom_id']);
    }
}
