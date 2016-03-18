<?php

namespace backend\models\purchase;

use Yii;
use backend\models\master\Branch;
use backend\models\master\Vendor;
use backend\models\inventory\GoodsMovement;
use yii\db\Query;

/**
 * This is the model class for table "purchase".
 *
 * @property integer $id
 * @property string $number
 * @property integer $vendor_id
 * @property integer $branch_id
 * @property string $date
 * @property double $value
 * @property double $discount
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property PurchaseDtl[] $items
 * @property Branch $branch 
 * @property Vendor $vendor
 */
class Purchase extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    // status movement
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CANCELED = 90;
    //document reff type
    const REFF_SELF = 10;
    const REFF_PURCH = 10;
    const REFF_PURCH_RETURN = 11;
    const REFF_GOODS_MOVEMENT = 20;
    const REFF_TRANSFER = 30;
    const REFF_INVOICE = 40;
    const REFF_PAYMENT = 50;
    const REFF_SALES = 60;
    const REFF_SALES_RETURN = 61;
    const REFF_JOURNAL = 70;
    // scenario
    const SCENARIO_CHANGE_STATUS = 'change_status';

    public $vendor_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%purchase}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'branch_id', 'Date', 'value', 'status'], 'required'],
            [['vendor_id', 'branch_id', 'status'], 'integer'],
            [['vendor_name', 'vendor_id', 'date'], 'safe'],
            [['number'], 'autonumber', 'format' => 'PU' . date('Ymd') . '.?', 'digit' => 4],
            [['items'], 'required', 'except' => self::SCENARIO_CHANGE_STATUS],
            [['items'], 'relationUnique', 'targetAttributes' => 'product_id', 'except' => self::SCENARIO_CHANGE_STATUS],
            [['value', 'discount'], 'number'],
            [['number'], 'string', 'max' => 16],
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
            'vendor_id' => 'Vendor ID',
            'branch_id' => 'Branch ID',
            'date' => 'Date',
            'value' => 'Value',
            'discount' => 'Discount',
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
        return $this->hasMany(PurchaseDtl::className(), ['purchase_id' => 'id']);
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
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(GoodsMovement::className(), ['reff_id' => 'id'])
                ->andOnCondition(['reff_type' => self::REFF_SELF]);
    }

    public function getNmStatus()
    {
        return $this->getLogical('status', 'STATUS_');
    }

    public function updateStatus()
    {
        return true;
    }

    public function generateReceive()
    {
        $queryGR = (new Query())
            ->select(['gmd.product_id', 'total' => 'sum(gmd.qty)'])
            ->from(['gm' => '{{%goods_movement}}'])
            ->innerJoin(['gmd' => '{{%goods_movement_dtl}}'], '[[gmd.movement_id]]=[[gm.id]]')
            ->where(['gm.status' => 20, 'gm.reff_type' => self::REFF_SELF, 'gm.reff_id' => $this->id])
            ->groupBy(['gmd.product_id']);
        $queryItem = (new Query())
            ->select(['pd.product_id', 'pd.price', 'pd.qty', 'pd.uom_id', 'g.total'])
            ->from(['pd' => '{{%purchase_dtl}}'])
            ->leftJoin(['g' => $queryGR], '[[g.product_id]]=[[pd.product_id]]')
            ->where(['pd.purchase_id' => $this->id]);
        $items = [];
        foreach ($queryItem->all() as $item) {
            $item['qty'] -= $item['total'];
            $item['cogs'] = $item['price'];
            $items[] = $item;
        }
        return $items;
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
            [
                'class' => 'dee\tools\StateChangeBehavior',
                'states' => [
                    [null, self::STATUS_RELEASED, 'updateStatus', 1],
                    [self::STATUS_DRAFT, self::STATUS_RELEASED, 'updateStatus', 1],
                    [self::STATUS_RELEASED, self::STATUS_DRAFT, 'updateStatus', -1],
                    [self::STATUS_RELEASED, self::STATUS_CANCELED, 'updateStatus', -1],
                    [self::STATUS_RELEASED, null, 'updateStatus', -1],
                ]
            ],
        ];
    }
}
