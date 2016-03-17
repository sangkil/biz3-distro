<?php

namespace backend\models\sales;

use Yii;
use backend\models\master\Product;
use backend\models\sales\PriceCategory;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "price".
 *
 * @property integer $product_id
 * @property integer $price_category_id
 * @property double $price
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property PriceCategory $priceCategory
 * @property Product $product
 */
class Price extends \yii\db\ActiveRecord {

    public $product_code;
    public $product_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'price_category_id'], 'required'],
            [['product_id', 'price_category_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['price'], 'number'],
            [['price_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceCategory::className(), 'targetAttribute' => ['price_category_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'price_category_id' => 'Price Category ID',
            'price' => 'Price',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceCategory() {
        return $this->hasOne(PriceCategory::className(), ['id' => 'price_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function behaviors() {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
