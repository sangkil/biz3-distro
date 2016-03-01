<?php

namespace backend\models\accounting;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[AccPeriode]].
 *
 * @see AccPeriode
 */
class AccPeriodeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return AccPeriode[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccPeriode|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function active() {
        return $this->andWhere(['=', 'status', AccPeriode::STATUS_OPEN])
                        ->andWhere(['<=', 'date_from', new Expression('date(NOW())')])
                        ->andWhere(['>=', 'date_to', new Expression('date(NOW())')]);
    }

    public function open() {
        return $this->andWhere(['=', 'status', AccPeriode::STATUS_OPEN]);
    }

}
