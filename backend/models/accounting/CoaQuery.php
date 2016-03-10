<?php

namespace backend\models\accounting;

/**
 * This is the ActiveQuery class for [[Coa]].
 *
 * @see Coa
 */
class CoaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Coa[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Coa|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function hasParent()
    {
        return $this->andWhere(['is not','parent_id',null]);
    }

    /**
     * 
     * @return static
     */
    public function codeOrdered()
    {
        return $this->orderBy(['code'=>SORT_ASC]);
    }
}
