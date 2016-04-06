<?php

namespace backend\models\accounting;

use Yii;
use backend\models\master\Vendor;
use mdm\converter\EnumTrait;
use mdm\behaviors\ar\RelationTrait;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property string $number
 * @property string $date
 * @property integer $type
 * @property integer $vendor_id
 * @property integer $payment_method
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property PaymentDtl[] $items
 * @property PaymentMethod $paymentMethod
 * @property Invoice[] $invoices
 * @property Vendor $vendor
 */
class Payment extends \yii\db\ActiveRecord
{

    use EnumTrait,
        RelationTrait;
    // status payment
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CLOSE = 90;
    // type payment
    const TYPE_INCOMING = 20;
    const TYPE_OUTGOING = 10;
       //document reff type
    const REFF_SELF = 50;
    const REFF_PURCH = 10;
    const REFF_PURCH_RETURN = 11;
    const REFF_GOODS_MOVEMENT = 20;
    const REFF_TRANSFER = 30;
    const REFF_INVOICE = 40;
    const REFF_PAYMENT = 50;
    const REFF_SALES = 60;
    const REFF_SALES_RETURN = 61;
    const REFF_JOURNAL = 70;

    public $vendor_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Date', 'type', 'payment_method', 'vendor_id', 'status'], 'required'],
            [['number'], 'autonumber', 'format' => 'PY' . date('Y') . '.?', 'digit' => 4],
            [['vendor_name', 'date'], 'safe'],
            [['items'], 'required'],
            [['items'], 'checkVendorAndType'],
            [['items'], 'relationUnique', 'targetAttributes' => 'invoice_id'],
            [['type', 'vendor_id', 'payment_method', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number'], 'string', 'max' => 16],
        ];
    }

    public function checkVendorAndType()
    {
        foreach ($this->items as $item) {
            if ($item->invoice->vendor_id != $this->vendor_id) {
                $this->addError('items', 'Vendor invoice tidak sama dengan vendor payment');
                break;
            }
            if ($item->invoice->type != $this->type) {
                $this->addError('items', 'Type invoice tidak sama dengan type payment');
                break;
            }
        }
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
            'type' => 'Type',
            'payment_method' => 'Payment Type',
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
        return $this->hasMany(PaymentDtl::className(), ['payment_id' => 'id']);
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
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['id' => 'invoice_id'])->viaTable('{{%payment_dtl}}', ['payment_id' => 'id']);
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
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method']);
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
