<?php

namespace backend\models\accounting;

use Yii;

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
 */
class Invoice extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    // status invoice
    const STATUS_DRAFT = 10;
    const STATUS_APPLIED = 20;
    const STATUS_CLOSE = 90;
    // type invoice
    const TYPE_IN = 10;
    const TYPE_OUT = 20;

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
            [['vendor_name'], 'safe'],
            [['number'], 'autonumber', 'format' => 'IV' . date('Ymd') . '.?', 'digit' => 4],
            [['items'], 'required'],
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
    public function getPaymentDtls()
    {
        return $this->hasMany(PaymentDtl::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['id' => 'payment_id'])->viaTable('payment_dtl', ['invoice_id' => 'id']);
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
