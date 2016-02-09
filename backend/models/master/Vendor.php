<?php

namespace backend\models\master;

use Yii;

/**
 * This is the model class for table "vendor".
 *
 * @property integer $id
 * @property integer $type
 * @property string $code
 * @property string $name
 * @property string $contact_name
 * @property string $contact_number
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property ProductVendor[] $productVendors
 * @property Product[] $products
 * @property VendorDetail $vendorDetail
 */
class Vendor extends \yii\db\ActiveRecord
{
    use \mdm\converter\EnumTrait;

    const TYPE_SUPPLIER = 10;
    const TYPE_CUSTOMER = 20;

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'code', 'name', 'status'], 'required'],
            [['type', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code'], 'string', 'max' => 8],
            [['name', 'contact_name', 'contact_number'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'code' => 'Code',
            'name' => 'Name',
            'contact_name' => 'Contact Name',
            'contact_number' => 'Contact Number',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getNmType()
    {
        return $this->getLogical('type', 'TYPE_');
    }

    public function getNmStatus()
    {
        return $this->getLogical('status', 'STATUS_');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductVendors()
    {
        return $this->hasMany(ProductVendor::className(), ['vendor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_vendor', ['vendor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDetail()
    {
        return $this->hasOne(VendorDetail::className(), ['id' => 'id']);
    }
}
