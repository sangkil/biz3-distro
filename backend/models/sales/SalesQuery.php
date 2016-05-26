<?php

namespace backend\models\sales;

/**
 * This is the ActiveQuery class for [[AccPeriode]].
 *
 * @see AccPeriode
 */
class SalesQuery extends \yii\db\ActiveQuery
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
        $dbranch = \app\models\master\U2Branch::find()
            ->select('branch_id')
            ->where('user_id=:duser', [':duser'=> \Yii::$app->user->id])
            ->column();
        return $this->andFilterWhere(['in', 'branch_id', count($dbranch)>0 ? $dbranch : [-1]]);
    }
}
