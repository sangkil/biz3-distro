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
            [['branch_id', 'warehouse_id'], 'integer'],
        ];
    }
}
