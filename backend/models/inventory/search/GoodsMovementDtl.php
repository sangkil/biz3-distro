<?php

namespace backend\models\inventory\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\inventory\GoodsMovementDtl as GoodsMovementDtlModel;

/**
 * GoodsMovementDtl represents the model behind the search form about `backend\models\inventory\GoodsMovementDtl`.
 */
class GoodsMovementDtl extends GoodsMovementDtlModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movement_id', 'product_id', 'uom_id'], 'integer'],
            [['qty', 'value', 'cogs'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = GoodsMovementDtlModel::find()
            ->select(['goods_movement_dtl.*','product.*'])
            ->with(['product','uom','movement'])
            ->joinWith(['product']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'movement_id' => $this->movement_id,
            'product_id' => $this->product_id,
            'uom_id' => $this->uom_id,
            'qty' => $this->qty,
            'value' => $this->value,
            'cogs' => $this->cogs,
        ]);

        return $dataProvider;
    }
}
