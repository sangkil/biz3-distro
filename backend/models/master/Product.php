<?php

namespace backend\models\master;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use backend\models\sales\Price;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $category_id
 * @property string $code
 * @property string $name
 * @property integer $status
 * @property boolean $stockable
 * @property string $edition
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Cogs $cogs
 * @property Price[] $prices
 * @property PriceCategory[] $priceCategories
 * @property Category $category
 * @property ProductGroup $group
 * @property ProductChild[] $productChildren
 * @property ProductStock[] $productStocks
 * @property Warehouse[] $warehouses
 * @property ProductVendor[] $productVendors
 * @property Vendor[] $vendors
 * @property ProductUom[] $productUoms
 * @property Uom[] $uoms
 */
class Product extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait;

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['group_id', 'category_id', 'code', 'name', 'status'], 'required'],
            [['group_id', 'category_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 64],
            [['stockable', 'edition', 'Edition'], 'safe'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductGroup::className(), 'targetAttribute' => ['group_id' => 'id']],
            ['code', 'unique', 'targetAttribute' => ['code'], 'message' => 'Code must be unique.'],
            ['name', 'unique', 'targetAttribute' => ['name'], 'message' => 'Name must be unique.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'category_id' => 'Category ID',
            'code' => 'Code',
            'name' => 'Name',
            'status' => 'Status',
            'edition' => 'Edition',
            'stockable' => 'Stockable',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCogs() {
        return $this->hasOne(Cogs::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices() {
        return $this->hasMany(Price::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceCategories() {
        return $this->hasMany(PriceCategory::className(), ['id' => 'price_category_id'])->viaTable('price', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup() {
        return $this->hasOne(ProductGroup::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductChildren() {
        return $this->hasMany(ProductChild::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductStocks() {
        return $this->hasMany(ProductStock::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses() {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])->viaTable('product_stock', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductVendors() {
        return $this->hasMany(ProductVendor::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendors() {
        return $this->hasMany(Vendor::className(), ['id' => 'vendor_id'])->viaTable('product_vendor', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductUoms() {
        return $this->hasMany(ProductUom::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUoms() {
        return $this->hasMany(Uom::className(), ['id' => 'uom_id'])->viaTable('product_uom', ['product_id' => 'id']);
    }

    public function getNmStatus() {
        return $this->getLogical('status', 'STATUS_');
    }

    public function behaviors() {
        return [
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'Edition' => 'edition', // date is original attribute
                ]
            ],
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
