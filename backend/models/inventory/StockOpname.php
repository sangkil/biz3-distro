<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Warehouse;

/**
 * This is the model class for table "{{%stock_opname}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $warehouse_id
 * @property string $date
 * @property integer $status
 * @property string $description
 * @property string $operator
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property \yii\web\UploadedFile $file Description
 *
 * @property StockOpnameDtl[] $items
 * @property Warehouse $warehouse
 */
class StockOpname extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    // status opname
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CANCELED = 90;
    //document reff type
    const REFF_SELF = 80;
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

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stock_opname}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'Date', 'status'], 'required'],
            [['!number'], 'autonumber', 'format' => 'SO' . date('Y') . '.?', 'digit' => 4],
            [['warehouse_id', 'status',], 'integer'],
            [['file'], 'file'],
            [['date'], 'safe'],
            [['description', 'operator'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'description' => 'Description',
            'operator' => 'Operator',
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
        return $this->hasMany(StockOpnameDtl::className(), ['opname_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    public function getNmStatus()
    {
        return $this->getLogical('status', 'STATUS_');
    }

    public function adjustStock()
    {
        $gm = new GoodsMovement([
            'warehouse_id' => $this->warehouse_id,
            'type' => GoodsMovement::TYPE_RECEIVE,
            'date' => date('Y-m-d'),
            'reff_type' => self::REFF_SELF,
            'reff_id' => $this->id,
            'status' => GoodsMovement::STATUS_RELEASED,
        ]);

        $query = (new \yii\db\Query())
            ->select(['p.id', 'selisih' => 'COALESCE(o.qty,0)-COALESCE(s.qty,0)'])
            ->from(['p' => '{{%product}}'])
            ->leftJoin(['s' => '{{%product_stock}}'], '[[s.product_id]]=[[p.id]] and [[s.warehouse_id]]=:whse', [':whse' => $this->warehouse_id])
            ->leftJoin(['o' => '{{%stock_opname_dtl}}'], '[[o.product_id]]=[[p.id]] and [[o.opname_id]]=:opid', [':opid' => $this->id])
            ->where('COALESCE(o.qty,0)<>COALESCE(s.qty,0))');

        $items = [];
        foreach ($query->all() as $row) {
            $items[] = [
                'product_id' => $row['id'],
                'qty' => $row['selisih'],
                'uom_id' => 1
            ];
        }
        $gm->items = $items;
        return $gm->save();
    }

    public function revertAdjust()
    {
        $gm = GoodsMovement::findOne([
                'reff_type' => self::REFF_SELF,
                'reff_id' => $this->id,
        ]);
        $gm->status = GoodsMovement::STATUS_CANCELED;
        return $gm->save();
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
                    [self::STATUS_DRAFT, self::STATUS_RELEASED, 'adjustStock'],
                    [self::STATUS_RELEASED, self::STATUS_CANCELED, 'revertAdjust'],
                ]
            ]
        ];
    }
}
