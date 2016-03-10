<?php

namespace backend\models\accounting;

/**
 * Description of GlTemplate
 *
 * @property EntriSheet $es
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 3.0
 */
class GlTemplate extends \yii\base\Model
{
    public $id;
    public $amount;

    public function rules()
    {
        return[
            [['id','amount'],'required']
        ];
    }
    /**
     *
     * @return EntriSheet
     */
    public function getEs()
    {
        if($this->id){
            return EntriSheet::findOne($this->id);
        }
    }
}
