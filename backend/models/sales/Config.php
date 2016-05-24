<?php

namespace backend\models\sales;

/**
 * Description of Config
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Config extends \yii\base\Model
{
    public $branch_id;
    public $warehouse_id;

    public function rules()
    {
        return[            
            [['branch_id', 'warehouse_id'], 'required'],
            [['branch_id', 'warehouse_id'], 'integer'],
        ];
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(\backend\models\master\Warehouse::className(), ['id' => 'warehouse_id']);
    }
}
