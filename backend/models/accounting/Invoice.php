<?php

namespace backend\models\accounting;

use Yii;
use backend\models\master\Vendor;
use yii\helpers\Html;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property string $number
 * @property string $date
 * @property string $due_date
 * @property integer $type
 * @property integer $vendor_id
 * @property integer $reff_type
 * @property integer $reff_id
 * @property integer $status
 * @property string $description
 * @property double $value
 * @property string $tax_type
 * @property double $tax_value
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property InvoiceDtl[] $items
 * @property PaymentDtl[] $paymentDtls
 * @property Payment[] $payments
 * @property Vendor $vendor
 */
class Invoice extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    // status invoice
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CANCELED = 90;
    // type invoice
    const TYPE_INCOMING = 10;
    const TYPE_OUTGOING = 20;
    //document reff type
    const REFF_SELF = 40;
    const REFF_PURCH = 10;
    const REFF_PURCH_RETURN = 11;
    const REFF_GOODS_MOVEMENT = 20;
    const REFF_TRANSFER = 30;
    const REFF_INVOICE = 40;
    const REFF_PAYMENT = 50;
    const REFF_SALES = 60;
    const REFF_SALES_RETURN = 61;
    const REFF_JOURNAL = 70;

    //const REFF_NOTHING = 90;

    public $vendor_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Date', 'DueDate', 'type', 'vendor_id', 'status', 'value'], 'required'],
            [['type', 'vendor_id', 'reff_type', 'reff_id', 'status'], 'integer'],
            [['value', 'tax_value'], 'number'],
            [['vendor_name', 'date', 'due_date'], 'safe'],
            [['number'], 'autonumber', 'format' => 'IV' . date('Y') . '.?', 'digit' => 4],
            [['items'], 'relationUnique', 'targetAttributes' => ['item_type', 'item_id']],
            [['description', 'tax_type'], 'string', 'max' => 64],
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
            'date' => 'Date',
            'due_date' => 'Due Date',
            'type' => 'Type',
            'vendor_id' => 'Vendor ID',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'status' => 'Status',
            'description' => 'Description',
            'value' => 'Value',
            'tax_type' => 'Tax Type',
            'tax_value' => 'Tax Value',
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
        return $this->hasMany(InvoiceDtl::className(), ['invoice_id' => 'id']);
    }

    /**
     *
     * @param array $value
     */
    public function setItems($value)
    {
        $this->loadRelated('items', $value);
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
    public function getJournals()
    {
        return $this->hasMany(GlHeader::className(), ['reff_id' => 'id'])->where(['reff_type' => GlHeader::REFF_INVOICE])->andFilterWhere(['<>',
                'status', GlHeader::STATUS_CANCELED]);
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
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['id' => 'payment_id'])->viaTable('payment_dtl', ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDtls()
    {
        return $this->hasMany(PaymentDtl::className(), ['invoice_id' => 'id']);
    }
    private $_paid;

    public function getPaid()
    {
        if ($this->_paid === null) {
            $this->_paid = (new \yii\db\Query())
                ->from('{{%payment_dtl}} pd')
                ->innerJoin('{{%payment}} p', '[[p.id]]=[[pd.payment_id]]')
                ->where(['pd.invoice_id' => $this->id, 'p.status' => Payment::STATUS_CLOSE])
                ->sum('pd.value');
        }
        return $this->_paid;
    }

    public function getSisa()
    {
        return $this->value - $this->getPaid();
    }

    public function createPayment($options = [], $value = null)
    {
        if ($this->status == self::STATUS_POSTED && ($paid = $this->getPaid()) > 0) {
            $payment = new Payment();
            $payment->attributes = array_merge([
                'date' => date('Y-m-d')
                ], $options, ['items' => []]);
            $payment->vendor_id = $this->vendor_id;
            $payment->status = Payment::STATUS_RELEASED;
            $payment->type = $this->type;
            $items = [
                [
                    'invoice_id' => $this->id,
                    'value' => $value !== null ? $value : $paid,
                ]
            ];
            $payment->items = $items;
            return $payment;
        }
        return false;
    }

    public function getNmReffType()
    {
        return $this->getLogical('reff_type', 'REFF_');
    }

    /**
     * @return String
     */
    public function getHyperlink()
    {
        return Html::a($this->number, ['/accounting/invoice/view', 'id' => $this->id]);
    }

    public function getReffNumber()
    {
        $link = null;
        switch ((int) $this->reff_type) {
            case (int) self::REFF_GOODS_MOVEMENT:
                $link = ($this->gMovement != null) ? Html::a($this->gMovement->number, ['/inventory/gm-manual/view', 'id' => $this->reff_id])
                        : '';
                break;
            default:
                break;
        }
        return $link;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGMovement()
    {
        return $this->hasOne(\backend\models\inventory\GoodsMovement::className(), ['id' => 'reff_id']);
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
                    'DueDate' => 'due_date'
                ]
            ],
            'yii\behaviors\BlameableBehavior',
            'yii\behaviors\TimestampBehavior',
        ];
    }
}
