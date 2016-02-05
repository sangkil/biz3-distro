<?php

namespace backend\models\master;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_supplier".
 *
 * @property integer $product_id
 * @property integer $supplier_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Product $product
 * @property Supplier $supplier
 */
class ProductSupplier extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'supplier_id'], 'required'],
            [['product_id', 'supplier_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'supplier_id' => 'Supplier ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
    public function getSupplier() {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function behaviors() {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
