<?php

namespace backend\models\accounting;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "gl_header".
 *
 * @property integer $id
 * @property string $number
 * @property string $date
 * @property integer $periode_id
 * @property integer $branch_id
 * @property integer $reff_type
 * @property integer $reff_id
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property GlDetail[] $glDetails
 * @property AccPeriode $periode
 */
class GlHeader extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CANCELED = 30;
    //document reff type
    const REFF_SELF = 70;
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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gl_header';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['glDetails', 'date', 'periode_id', 'branch_id', 'reff_type', 'description', 'status'], 'required'],
            [['number'], 'autonumber', 'format' => 'GL' . date('Y') . '.?', 'digit' => 4],
            [['date', 'GlDate'], 'safe'],
            [['periode_id', 'branch_id', 'reff_type', 'reff_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'integer'],
            [['number'], 'string', 'max' => 16],
            [['description'], 'string', 'max' => 255],
            [['glDetails'], 'validateDualEntri'],
            [['periode'], 'validateOpenPeriode'],
            [['periode_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccPeriode::className(), 'targetAttribute' => ['periode_id' => 'id']],
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
            'periode_id' => 'Periode ID',
            'branch_id' => 'Branch ID',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     *
     * @param array $templates
     */
    public function addFromTemplate($templates = [], $desc = false)
    {
        $items = $this->glDetails;
        foreach ($templates as $code => $amount) {
            /* @var $es EntriSheet */
            $es = EntriSheet::findOne(['code' => $code]);
            if ($desc) {
                $this->description .= "\n" . $es->name;
            }
            $items[] = [
                'coa_id' => $es->d_coa_id,
                'amount' => $amount,
            ];
            $items[] = [
                'coa_id' => $es->k_coa_id,
                'amount' => -1 * $amount,
            ];
        }
        $this->glDetails = $items;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlDetails()
    {
        return $this->hasMany(GlDetail::className(), ['header_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function setGlDetails($value)
    {
        $this->loadRelated('glDetails', $value);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriode()
    {
        return $this->hasOne(AccPeriode::className(), ['id' => 'periode_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(\backend\models\master\Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGMovement()
    {
        return $this->hasOne(\backend\models\inventory\GoodsMovement::className(), ['id' => 'reff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(\backend\models\accounting\Invoice::className(), ['id' => 'reff_id']);
    }

    /**
     * @return String
     */
    public function getHyperlink()
    {
        return Html::a($this->number, ['/accounting/general-ledger/view', 'id' => $this->id]);
    }

    public function getNmStatus()
    {
        return $this->getLogical('status', 'STATUS_');
    }

    public function getNmReffType()
    {
        return $this->getLogical('reff_type', 'REFF_');
    }

    public function getReffNumber()
    {
        $link = null;
        switch ((int) $this->reff_type) {
            case (int) self::REFF_GOODS_MOVEMENT:
                $link = ($this->gMovement != null) ? Html::a($this->gMovement->number, ['/inventory/gm-manual/view', 'id' => $this->reff_id])
                        : '';
                break;
            case (int) self::REFF_INVOICE:
                $link = ($this->invoice != null) ? Html::a($this->invoice->number, ['/accounting/invoice/view', 'id' => $this->reff_id])
                        : '';
                break;
            case (int) self::REFF_JOURNAL:
                $link = ($this->journal != null) ? Html::a($this->journal->number, ['/accounting/general-ledger/view', 'id' => $this->reff_id])
                        : '';
                break;

            default:
                break;
        }
        //echo $this->reff_type.'vs'.self::REFF_JOURNAL;
        return $link;
    }

    public function validateDualEntri($attribute)
    {
        if ($this->$attribute != null) {
            $totAmount = 0;
            foreach ($this->$attribute as $valc) {
                $totAmount += $valc->amount;
            }
            if ($totAmount != 0) {
                $this->addError($attribute, "Total Debit must equal to Total Credit");
            }
        }
    }

    public function validateOpenPeriode($attribute)
    {
        if ($this->$attribute->status != AccPeriode::STATUS_OPEN) {
            $this->addError($attribute, "Accounting Periode has been closed");
        }
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'GlDate' => 'date',
                ]
            ],
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }
}
