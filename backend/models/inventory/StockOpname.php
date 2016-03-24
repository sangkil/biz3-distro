<?php

namespace backend\models\inventory;

use Yii;

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
 * @property StockOpnameDtl[] $stockOpnameDtls
 */
class StockOpname extends \yii\db\ActiveRecord
{
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
            [['number', 'warehouse_id', 'date', 'status'], 'required'],
            [['warehouse_id', 'status',], 'integer'],
            [['file'], 'file'],
            [['date'], 'safe'],
            [['number'], 'string', 'max' => 16],
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
    public function getStockOpnameDtls($selisih = false)
    {
        $query = $this->hasMany(StockOpnameDtl::className(), ['opname_id' => 'id']);
        if ($selisih) {
            $query->alias('od');
            $query->leftJoin(['s' => '{{%product_stock}}'], '[[s.product_id]]=[[od.product_id]]')
                ->andWhere('[[s.qty]]<>[[od.qty]]')
                ->andWhere(['s.warehouse_id' => $this->warehouse_id]);
        }
        return $query;
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
