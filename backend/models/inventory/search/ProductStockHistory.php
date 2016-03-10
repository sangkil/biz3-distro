<?php

namespace app\models\inventory\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\inventory\ProductStockHistory as ProductStockHistoryModel;

/**
 * ProductStockHistory represents the model behind the search form about `app\models\inventory\ProductStockHistory`.
 */
class ProductStockHistory extends ProductStockHistoryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'qty_movement', 'qty_current'], 'number'],
            [['warehouse_id', 'product_id', 'movement_id'], 'integer'],
            [['product_name', 'product_code'], 'safe'],
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
        $query = ProductStockHistoryModel::find();
        $query->select(['product_stock_history.*', 'product.*', 'warehouse.*']);
        $query->joinWith(['product', 'warehouse']);
        $query->orderBy(['product.name'=>SORT_ASC,'time'=>SORT_ASC ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'time' => $this->time,
            'warehouse_id' => $this->warehouse_id,
            'product_id' => $this->product_id,
            'qty_movement' => $this->qty_movement,
            'qty_current' => $this->qty_current,
            'movement_id' => $this->movement_id,
        ]);
        $query->andWhere(['like', 'lower(product.name)', strtolower($this->product_name)]);
        $query->andWhere(['like', 'lower(product.code)', strtolower($this->product_code)]);

        return $dataProvider;
    }
}
