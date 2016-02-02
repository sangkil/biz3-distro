<?php

namespace backend\models\inventory;

use Yii;

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
 * @property TransferDtl[] $transferDtls
 */
class Transfer extends \yii\db\ActiveRecord
{
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
            [['number', 'branch_id', 'branch_dest_id', 'date', 'status'], 'required'],
            [['branch_id', 'branch_dest_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date'], 'safe'],
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
    public function getTransferDtls()
    {
        return $this->hasMany(TransferDtl::className(), ['transfer_id' => 'id']);
    }
}
