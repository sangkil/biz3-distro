<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Branch;
use yii\db\Query;

/**
 * This is the model class for table "transfer".
 *
 * @property integer $id
 * @property string $number
 * @property integer $branch_id
 * @property integer $branch_dest_id
 * @property string $date
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property TransferDtl[] $items
 * @property Branch $branch
 * @property BranchDest $branch
 */
class Transfer extends \yii\db\ActiveRecord
{

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;
    // status transfer
    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;
    const STATUS_CLOSED = 90;
    // scenario
    const SCENARIO_CHANGE_STATUS = 'change_status';
    //document reff type
    const REFF_SELF = 30;
    const REFF_PURCH = 10;
    const REFF_PURCH_RETURN = 11;
    const REFF_GOODS_MOVEMENT = 20;
    const REFF_TRANSFER = 30;
    const REFF_INVOICE = 40;
    const REFF_PAYMENT = 50;
    const REFF_SALES = 60;
    const REFF_SALES_RETURN = 61;
    const REFF_JOURNAL = 70;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'branch_dest_id', 'Date', 'status'], 'required'],
            [['number'], 'autonumber', 'format' => 'IT' . date('Y') . '.?', 'digit' => 4],
            [['branch_id', 'branch_dest_id', 'status'], 'integer'],
            [['date'], 'safe'],
            [['items'], 'required', 'except' => self::SCENARIO_CHANGE_STATUS],
            [['items'], 'relationUnique', 'targetAttributes' => 'product_id', 'except' => self::SCENARIO_CHANGE_STATUS],
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
            'branch_id' => 'Branch ID',
            'branch_dest_id' => 'Branch Dest ID',
            'date' => 'Date',
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
        return $this->hasMany(TransferDtl::className(), ['transfer_id' => 'id']);
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
    public function getBranchDest()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_dest_id']);
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

    public function generateReceive()
    {
        $queryGM = (new Query())
            ->select(['gmd.product_id', 'total' => 'sum(gmd.qty)'])
            ->from(['gm' => '{{%goods_movement}}'])
            ->innerJoin(['gmd' => '{{%goods_movement_dtl}}'], '[[gmd.movement_id]]=[[gm.id]]')
            ->where(['gm.status' => 20, 'gm.reff_type' => self::REFF_SELF, 'gm.reff_id' => $this->id])
            ->groupBy(['gmd.product_id']);
        $queryItem = (new Query())
            ->select(['td.product_id', 'c.cogs', 'td.qty', 'td.uom_id', 'g.total'])
            ->from(['td' => '{{%transfer_dtl}}'])
            ->leftJoin(['c'=>'{{%cogs}}'], '[[c.product_id]]=[[td.product_id]]')
            ->leftJoin(['g' => $queryGM], '[[g.product_id]]=[[td.product_id]]')
            ->where(['td.transfer_id' => $this->id]);
        $items = [];
        foreach ($queryItem->all() as $item) {
            $item['qty'] -= $item['total'];
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
        ];
    }
}
