<?php

namespace backend\models\inventory;

use Yii;
use backend\models\master\Product;
use backend\models\master\Uom;

/**
 * This is the model class for table "transfer_dtl".
 *
 * @property integer $transfer_id
 * @property integer $product_id
 * @property integer $uom_id
 * @property double $qty
 * @property double $total_release
 * @property double $total_receive
 *
 * @property Transfer $transfer
 * @property Product $product
 */
class TransferDtl extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer_dtl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'uom_id'], 'required'],
            [['transfer_id', 'product_id', 'uom_id'], 'integer'],
            [['qty', 'total_release', 'total_receive'], 'number'],
            [['qty'], 'number','min'=>1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transfer_id' => 'Transfer ID',
            'product_id' => 'Product ID',
            'uom_id' => 'Uom ID',
            'qty' => 'Qty',
            'total_release' => 'Total Release',
            'total_receive' => 'Total Receive',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfer()
    {
        return $this->hasOne(Transfer::className(), ['id' => 'transfer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUom()
    {
        return $this->hasOne(Uom::className(), ['id' => 'uom_id']);
    }
}
