<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Branch;

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
    const STATUS_APPLIED = 20;
    const STATUS_CLOSE = 90;

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
            [['number'], 'autonumber', 'format' => 'IT' . date('Ymd') . '.?', 'digit' => 4],
            [['branch_id', 'branch_dest_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['items'], 'required',],
            [['items'], 'relationUnique', 'targetAttributes' => 'product_id',],
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
