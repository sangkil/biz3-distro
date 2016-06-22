<?php

namespace backend\models\sales;

use Yii;
use backend\models\master\Branch;
use backend\models\master\Vendor;
use backend\models\inventory\GoodsMovement;
use backend\models\accounting\GlHeader;
use backend\models\accounting\PaymentDtl;

/**
 * This is the model class for table "sales".
 *
 * @property integer $id
 * @property string $number
 * @property integer $vendor_id
 * @property integer $branch_id
 * @property integer $reff_type
 * @property integer $reff_id
 * @property string $date
 * @property double $value
 * @property double $discount
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property SalesDtl[] $items
 * @property Branch $branch 
 * @property Vendor $vendor
 * @property Payment $payments
 */
class Sales extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;

    // status movement
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CLOSE = 90;
    //document reff type
    const REFF_SELF = 60;
    const REFF_PURCH = 10;
    const REFF_PURCH_RETURN = 11;
    const REFF_GOODS_MOVEMENT = 20;
    const REFF_TRANSFER = 30;
    const REFF_INVOICE = 40;
    const REFF_PAYMENT = 50;
    const REFF_SALES = 60;
    const REFF_SALES_RETURN = 61;
    const REFF_JOURNAL = 70;
    //hardcode pos vendor
    const DEFAULT_VENDOR = 11;

    public $vendor_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%sales}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['branch_id', 'Date', 'value', 'status'], 'required'],
            [['vendor_id', 'branch_id', 'reff_type', 'reff_id', 'status'], 'integer'],
            [['vendor_name'], 'safe'],
            [['number'], 'autonumber', 'format' => 'SA' . date('Ym') . '.?', 'digit' => 4],
            [['items'], 'required'],
            [['items'], 'relationUnique', 'targetAttributes' => 'product_id'],
            [['value', 'discount'], 'number'],
            [['number'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
    public function getItems() {
        return $this->hasMany(SalesDtl::className(), ['sales_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments() {
        return $this->hasMany(PaymentDtl::className(), ['invoice_id' => 'reff_id'])->with(['payment']);
    }

    /**
     *
     * @param array $value
     */
    public function setItems($value) {
        $this->loadRelated('items', $value);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch() {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKasir() {
        return $this->hasOne(\mdm\admin\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor() {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    public function getNmStatus() {
        return $this->getLogical('status', 'STATUS_');
    }

    /**
     * 
     * @param GlHeader $model
     */
    public function createUpdateJournal($model = null) {
        if ($model === null) {
            $model = new GlHeader([
                'status' => GlHeader::STATUS_RELEASED,
                'reff_type' => GlHeader::REFF_SALES,
                'reff_id' => $this->id,
                'date' => date('Y-m-d'),
                'vendor_id' => $this->vendor_id,
                'periode_id' => GlHeader::getActivePeriode(),
                'branch_id' => $this->branch_id,
            ]);
        }

        $value = 0;
        foreach ($this->items as $item) {
            $value += $item->cogs * $item->qty * $item->productUom->isi;
        }

        $model->addFromTemplate([
            'ES006' => $value,
        ]);
        return $model;
    }

    /**
     *
     * @param array $options
     * @param array $data
     * @return GoodsMovement|boolean
     */
    public function createMovement($options = [], $data = null) {
        if ($this->status == self::STATUS_RELEASED) {
            $movement = new GoodsMovement();
            $movement->attributes = array_merge([
                'date' => date('Y-m-d'),
                    ], $options);
            $movement->reff_type = GoodsMovement::REFF_SALES;
            $movement->reff_id = $this->id;
            $movement->type = GoodsMovement::TYPE_ISSUE;
            $movement->status = GoodsMovement::STATUS_RELEASED;
            $movement->vendor_id = $this->vendor_id;

            $sqlMv = (new \yii\db\Query())
                    ->select(['d.product_id', 'total' => 'sum(d.qty)'])
                    ->from('{{%goods_movement_dtl}} d')
                    ->innerJoin('{{%goods_movement}} m', '[[m.id]]=[[d.movement_id]]')
                    ->where(['m.status' => GoodsMovement::STATUS_RELEASED,
                        'm.reff_type' => GoodsMovement::REFF_SALES, 'm.reff_id' => $this->id])
                    ->groupBy(['d.product_id']);

            $sql = (new \yii\db\Query())
                    ->select(['sd.*', 'mv.total'])
                    ->from('{{%sales_dtl}} sd')
                    ->leftJoin(['mv' => $sqlMv], '[[sd.product_id]]=[[mv.product_id]]')
                    ->where(['sd.sales_id' => $this->id]);

            $items = [];
            $details = $sql->indexBy('product_id')->all();

            if ($data !== null) {
                foreach ($data as $row) {
                    $detail = $details[$row['product_id']];
                    $items[] = array_merge([
                        'qty' => $detail['qty'] - $detail['total'],
                        'uom_id' => $detail['uom_id'],
                        'value' => $detail['price'] * (1 - 0.01 * $detail['discount']),
                        'cogs' => $detail['cogs'],
                            ], $row);
                }
            } else {
                foreach ($details as $detail) {
                    $items[] = [
                        'product_id' => $detail['product_id'],
                        'qty' => $detail['qty'] - $detail['total'],
                        'uom_id' => $detail['uom_id'],
                        'value' => $detail['price'] * (1 - 0.01 * $detail['discount']),
                        'cogs' => $detail['cogs'],
                    ];
                }
            }
            $movement->items = $items;
            return $movement;
        }
        return false;
    }

    public function behaviors() {
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

    public static function find() {
        return new SalesQuery(get_called_class());
    }

}
