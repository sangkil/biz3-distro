<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Warehouse;
use backend\models\master\Vendor;

/**
 * This is the model class for table "goods_movement".
 *
 * @property integer $id
 * @property string $number
 * @property integer $warehouse_id
 * @property string $date
 * @property integer $type
 * @property integer $reff_type
 * @property integer $reff_id
 * @property integer $vendor_id
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property GoodsMovementDtl[] $items
 * @property Warehouse $warehouse
 * @property Vendor $vendor
 */
class GoodsMovement extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    
    // status movement
    const STATUS_DRAFT = 10;
    const STATUS_APPLIED = 20;
    const STATUS_CLOSE = 90;
    // type movement
    const TYPE_RECEIVE = 10;
    const TYPE_ISSUE = 20;

    public $vendor_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_movement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'Date', 'type', 'status'], 'required'],
            [['number'], 'autonumber', 'format' => 'GM' . date('Ymd') . '.?', 'digit' => 4],
            [['warehouse_id', 'type', 'reff_type', 'reff_id', 'vendor_id', 'status'], 'integer'],
            [['items'], 'required'],
            [['vendor_name'], 'safe'],
            [['items'], 'relationUnique', 'targetAttributes' => 'product_id'],
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
            'type' => 'Type',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'vendor_id' => 'Vendor ID',
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
    public function getItems()
    {
        return $this->hasMany(GoodsMovementDtl::className(), ['movement_id' => 'id']);
    }

    /**
     *
     * @param array $value
     */
    public function setItems($value)
    {
        $this->loadRelated('items', $value);
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
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    public function getNmType()
    {
        return $this->getLogical('type', 'TYPE_');
    }

    public function getNmStatus()
    {
        return $this->getLogical('status', 'STATUS_');
    }

    public function behaviors()
    {
        return[
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'Date' => 'date', // date is original attribute
                ]
            ],
            'yii\behaviors\BlameableBehavior',
            'yii\behaviors\TimestampBehavior',
        ];
    }
}
