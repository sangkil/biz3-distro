<?php

namespace backend\models\master;

use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[AccPeriode]].
 *
 * @see AccPeriode
 */
class WarehouseQuery extends \yii\db\ActiveQuery
{
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return AccPeriode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccPeriode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function assigned()
    {
        $dwhse = \app\models\master\U2Warehouse::find()
            ->select('warehouse_id')
            ->where('user_id=:duser', [':duser'=> \Yii::$app->user->id])
            ->column();
        return $this->andFilterWhere(['in', 'id', count($dwhse)>0 ? $dwhse : [-1]]);
    }
}
