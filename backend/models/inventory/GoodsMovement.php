<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Warehouse;
use backend\models\master\Vendor;
use backend\models\master\ProductUom;
use backend\models\master\ProductStock;
use backend\models\accounting\Invoice;
use backend\models\accounting\GlHeader;
use backend\models\accounting\EntriSheet;
use backend\models\master\Cogs;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

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
 * @property boolean $stateChanged
 *
 * @property GoodsMovementDtl[] $items
 * @property Warehouse $warehouse
 * @property Vendor $vendor
 */
class GoodsMovement extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;

    // status movement
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CANCELED = 90;
    // type movement
    const TYPE_RECEIVE = 10;
    const TYPE_ISSUE = 20;
    //document reff type
    const REFF_SELF = 20;
    const REFF_PURCH = 10;
    const REFF_PURCH_RETURN = 11;
    const REFF_GOODS_MOVEMENT = 20;
    const REFF_TRANSFER = 30;
    const REFF_INVOICE = 40;
    const REFF_PAYMENT = 50;
    const REFF_SALES = 60;
    const REFF_SALES_RETURN = 61;
    const REFF_JOURNAL = 70;
    const REFF_OPNAME = 80;
    // scenario
    const SCENARIO_CHANGE_STATUS = 'change_status';

    public $vendor_name, $whse_name, $jml;

    /**
     * @var array
     */
    public static $references;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%goods_movement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['warehouse_id', 'Date', 'type', 'status'], 'required'],
            [['!number'], 'autonumber', 'format' => 'GM' . date('Y') . '.?', 'digit' => 4],
            [['warehouse_id', 'type', 'reff_type', 'reff_id', 'vendor_id', 'status'], 'integer'],
            [['items'], 'required', 'except' => self::SCENARIO_CHANGE_STATUS],
            [['vendor_name', 'vendor_id', 'date'], 'safe'],
            [['items'], 'relationUnique', 'targetAttributes' => 'product_id', 'except' => self::SCENARIO_CHANGE_STATUS],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
    public function getItems() {
        return $this->hasMany(GoodsMovementDtl::className(), ['movement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTotalValue() {
        $totValue = 0;
        foreach ($this->items as $itemDtl) {
            $isi = (isset($model->productUom->isi)) ? $model->productUom->isi : 1;
            $totValue += $itemDtl->qty * $itemDtl->cogs * $isi;
        }
        return $totValue;
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
    public function getWarehouse() {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor() {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice() {
        return $this->hasOne(Invoice::className(), ['reff_id' => 'id'])->where(['reff_type' => self::REFF_GOODS_MOVEMENT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournals() {
        return $this->hasMany(GlHeader::className(), ['reff_id' => 'id'])->where(['reff_type' => GlHeader::REFF_GOODS_MOVEMENT])->andFilterWhere(['<>',
                    'status', GlHeader::STATUS_CANCELED]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfer() {
        return $this->hasOne(Transfer::className(), ['id' => 'reff_id']);
    }

    public function getNmType() {
        return $this->getLogical('type', 'TYPE_');
    }

    public function getNmStatus() {
        return $this->getLogical('status', 'STATUS_');
    }

    public function getNmReffType() {
        return $this->getLogical('reff_type', 'REFF_');
    }

    public function getReffNumber() {
        $link = null;
        switch ((int) $this->reff_type) {
            case (int) self::REFF_TRANSFER :
                $link = ($this->transfer != null) ? Html::a($this->transfer->number, ['/inventory/transfer/view', 'id' => $this->reff_id]) : '';
                break;
            default:
                break;
        }
        return $link;
    }

    /**
     *
     * @return boolean
     */
    public function updateStock($factor) {
        // update stock
        $wh_id = $this->warehouse_id;
        $mv_id = $this->id;
        $factor = $factor * ($this->type == self::TYPE_RECEIVE ? 1 : -1);
        $command = Yii::$app->db->createCommand();
        foreach ($this->items as $item) {
            $product_id = $item->product_id;
            $pu = ProductUom::findOne(['product_id' => $product_id, 'uom_id' => $item->uom_id]);
            $qty = $factor * $item->qty * ($pu ? $pu->isi : 1);
            $ps = ProductStock::findOne(['product_id' => $product_id, 'warehouse_id' => $wh_id]);
            if ($ps) {
                $ps->qty = new Expression('[[qty]] + :added', [':added' => $qty]);
            } else {
                $ps = new ProductStock(['product_id' => $product_id, 'warehouse_id' => $wh_id, 'qty' => $qty]);
            }
            if (!$ps->save(false) || !$ps->refresh() || !$command->insert('{{%product_stock_history}}', [
                        'time' => microtime(true),
                        'warehouse_id' => $wh_id,
                        'product_id' => $product_id,
                        'qty_movement' => $qty,
                        'qty_current' => $ps->qty,
                        'movement_id' => $mv_id,
                    ])->execute()) {
                return false;
            }

            $paramCogs = [
                'id' => $product_id,
                'qty' => $qty,
                'cogs' => $item->cogs,
            ];
            if (!$this->updateCogs($paramCogs)) {
                return false;
            }
        }
        return true;
    }

    protected function updateCogs($params) {
        $cogs = Cogs::findOne(['product_id' => $params['id']]);
        if (!$cogs) {
            $cogs = new Cogs([
                'product_id' => $params['id'],
                'cogs' => 0.0
            ]);
        }
        $current_stock = ProductStock::find()
                ->where(['product_id' => $params['id']])
                ->sum('qty');

        $cogs->last_purchase_price = $params['cogs'];
        if ($current_stock != 0) {
            $cogs->cogs += ($params['qty'] * ($params['cogs'] - $cogs->cogs)) / $current_stock;
        } else {
            $cogs->cogs = 0;
        }

        return $cogs->save(false);
    }

    /**
     *
     * @return boolean
     */
    public function postGL($factor) {
        /*
         * Header Journal
         */
        $model_journal = new \backend\models\accounting\GlHeader;
        $model_journal->periode_id = $this->findActivePeriode();
        $model_journal->date = date('Y-m-d');
        $model_journal->status = \backend\models\accounting\GlHeader::STATUS_RELEASED;
        $model_journal->reff_type = \backend\models\accounting\GlHeader::REFF_GOODS_MOVEMENT;
        $model_journal->reff_id = $this->id;
        $model_journal->branch_id = (isset(Yii::$app->profile->branch_id)) ? Yii::$app->profile->branch_id : -1;

        $esheet = null;
        switch ($this->reff_type):
            case self::REFF_TRANSFER:
                $esheet = ($this->type == self::TYPE_ISSUE) ?
                        EntriSheet::find()->where('code=:dcode', [':dcode' => 'ES004'])->one() :
                        EntriSheet::find()->where('code=:dcode', [':dcode' => 'ES005'])->one();
                break;
            default :
                $esheet = ($factor == 1) ? EntriSheet::find()->where('code=:dcode', [':dcode' => 'ES001'])->one() : EntriSheet::find()->where('code=:dcode', [':dcode' => 'ES003'])->one();
        endswitch;

        $model_journal->description = $esheet->name;

        /*
         * Detail Journal
         */
        $newDtls = [];

        $ndtl = new \backend\models\accounting\GlDetail();
        $ndtl->coa_id = $esheet->d_coa_id;
        $ndtl->header_id = null;
        $ndtl->amount = $this->totalValue;
        $newDtls[] = $ndtl;

        $ndtl1 = new \backend\models\accounting\GlDetail();
        $ndtl1->coa_id = $esheet->k_coa_id;
        $ndtl1->header_id = null;
        $ndtl1->amount = $this->totalValue * -1;
        $newDtls[] = $ndtl1;

        $model_journal->glDetails = $newDtls;

        if (!$model_journal->save()) {
            print_r($model_journal->getErrors());
            print_r($model_journal->getRelatedErrors());
            return false;
        }
        return true;
    }

    /**
     *
     * @param array $options
     * @return Invoice|boolean
     */
    public function createInvoice($options = []) {
        if ($this->status == self::STATUS_RELEASED) {
            $oldInvoice = Invoice::findOne(['reff_type' => Invoice::REFF_GOODS_MOVEMENT,
                        'reff_id' => $this->id, 'status' => Invoice::STATUS_RELEASED]);

            if ($oldInvoice !== null) {
                return false;
            }
            $invoice = new Invoice();
            $invoice->attributes = array_merge([
                'date' => date('Y-m-d'),
                'due_date' => date('Y-m-d', time() + 30 * 24 * 3600)
                    ], $options);
            $invoice->reff_type = Invoice::REFF_GOODS_MOVEMENT;
            $invoice->reff_id = $this->id;
            $invoice->vendor_id = $this->vendor_id;
            $invoice->type = $this->type == self::TYPE_RECEIVE ? Invoice::TYPE_INCOMING : Invoice::TYPE_OUTGOING;
            $invoice->status = Invoice::STATUS_RELEASED;

            $items = [];
            /* @var $item GoodsMovementDtl */
            $total = 0;
            foreach ($this->items as $item) {
                $p_id = $item->product_id;
                $pu = ProductUom::findOne(['product_id' => $p_id, 'uom_id' => $item->uom_id]);
                $qty = ($pu ? $pu->isi : 1) * $item->qty;
                $total += $qty * $item->value;
                $items[] = [
                    'item_type' => 10,
                    'item_id' => $item->product_id,
                    'qty' => $qty,
                    'item_value' => $item->value,
                ];
            }
            $invoice->value = $total;
            $invoice->items = $items;
            return $invoice;
        }
        return false;
    }

    public function getReference($withItems = true) {
        $type = $this->reff_type;
        $id = $this->reff_id;

        $reff = static::$references[$type];
        $class = $reff['class'];
        if (isset($reff['onlyStatus'])) {
            $key = ['id' => $id, 'status' => $reff['onlyStatus']];
        } else {
            $key = $id;
        }
        $reffModel = $class::findOne($key);
        if ($reffModel === null) {
            return false;
        }

        // items
        $items = false;
        if ($withItems && isset($reff['items'])) {
            $items = call_user_func([$reffModel, $reff['items']]);
        }
        return[$reffModel, $reff, $items];
    }

    /**
     * Update information from reference
     * @return boolean
     */
    public function updateFromReference() {
        if (($reff = $this->getReference()) === false) {
            return false;
        }
        list($reffModel, $reff, $items) = $reff;

        $this->type = $reff['type'];
        // vendor
        if (isset($reff['vendor'])) {
            $this->vendor_id = $reffModel->{$reff['vendor']};
        }
        // warehouse
        if (isset($reff['warehouse'])) {
            $this->warehouse_id = $reffModel->{$reff['warehouse']};
        }

        // items
        if ($items !== false) {
            $oldItems = ArrayHelper::index($this->items, 'product_id');
            $isNew = empty($oldItems);
            foreach ($items as $i => $item) {
                $pid = $item['product_id'];
                if (isset($oldItems[$pid])) {
                    $items[$i] = $oldItems[$pid];
                } else {
                    $items[$i] = new GoodsMovementDtl();
                    $items[$i]->attributes = $item;
                    if (!$isNew) {
                        $items[$i]->qty = '';
                    }
                }
                $items[$i]->sisa = $item['qty'];
            }

            $this->populateRelation('items', $items);
        }
        return[$reffModel, $reff, $items];
    }

    /**
     * Update information from reference
     * @return boolean
     */
    public function updateFromReceipt() {
        if (($reff = $this->getReference()) === false) {
            return false;
        }
        list($reffModel, $reff, $items) = $reff;

        $this->type = $reff['type'];
        // vendor
        if (isset($reff['vendor'])) {
            $this->vendor_id = $reffModel->{$reff['vendor']};
        }
        // warehouse
        if (isset($reff['warehouse'])) {
            $this->warehouse_id = $reffModel->{$reff['warehouse']};
        }

        // items
        if ($items !== false) {
            $oldItems = ArrayHelper::index($this->items, 'product_id');
            $isNew = empty($oldItems);
            foreach ($items as $i => $item) {
                $pid = $item['product_id'];
                if (isset($oldItems[$pid])) {
                    $items[$i] = $oldItems[$pid];
                } else {
                    $items[$i] = new GoodsMovementDtl();
                    $items[$i]->attributes = $item;
                    if (!$isNew) {
                        $items[$i]->qty = '';
                    }
                }
                $items[$i]->sisa = $item['qty'];
            }
            $this->populateRelation('items', $items);
        }
        return[$reffModel, $reff, $items];
    }

    public function generateReceiveFromIssueTransfer() {
        $queryGM = (new Query())
                ->select(['gmd.product_id', 'total' => 'sum(gmd.qty)'])
                ->from(['gm' => '{{%goods_movement}}'])
                ->innerJoin(['gmd' => '{{%goods_movement_dtl}}'], '[[gmd.movement_id]]=[[gm.id]]')
                ->where(['gm.status' => 20, 'gm.reff_type' => self::REFF_SELF, 'gm.reff_id' => $this->id])
                ->groupBy(['gmd.product_id']);
        $queryItem = (new Query())
                ->select(['md.product_id', 'md.cogs', 'md.qty', 'md.uom_id', 'g.total'])
                ->from(['md' => '{{%goods_movement_dtl}}'])
                ->leftJoin(['g' => $queryGM], '[[g.product_id]]=[[md.product_id]]')
                ->where(['md.movement_id' => $this->id]);
        $items = [];
        foreach ($queryItem->all() as $item) {
            $item['qty'] -= $item['total'];
            $items[] = $item;
        }
        return $items;
    }

    protected function findActivePeriode() {
        $dPeriode = \backend\models\accounting\AccPeriode::find()->active()->one();
        if ($dPeriode != null) {
            return $dPeriode->id;
        }

        throw new NotFoundHttpException('There is no active periode..');
    }

    /**
     * Execute before child save. If return false, child not saved
     * @param GoodsMovementDtl $child
     * @return boolean Description
     */
    public function beforeRSave($child) {
        return $child->qty != 0;
    }

    /**
     * @inheritdoc
     */
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
            [
                'class' => 'dee\tools\StateChangeBehavior',
                'states' => [
                    [null, self::STATUS_RELEASED, 'updateStock', 1],
                    [self::STATUS_DRAFT, self::STATUS_RELEASED, 'updateStock', 1],
                    [self::STATUS_DRAFT, self::STATUS_RELEASED, 'postGL', 1],
                    [self::STATUS_RELEASED, self::STATUS_DRAFT, 'updateStock', -1],
                    [self::STATUS_RELEASED, self::STATUS_DRAFT, 'postGL', -1],
                    [self::STATUS_RELEASED, self::STATUS_CANCELED, 'updateStock', -1],
                    [self::STATUS_RELEASED, self::STATUS_CANCELED, 'postGL', -1],
                    [self::STATUS_RELEASED, null, 'updateStock', -1],
                ]
            ]
        ];
    }

}

GoodsMovement::$references = require('mv_reference.php');
