<?php

namespace backend\models\inventory\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\inventory\ProductStock as ProductStockModel;

/**
 * ProductStock represents the model behind the search form about `backend\models\inventory\ProductStock`.
 */
class ProductStock extends ProductStockModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'product_id', 'qty', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
        $query = ProductStockModel::find();
        $query->select(['product_stock.*', 'product.*', 'warehouse.*']);
        $query->with(['product.group']);
        $query->joinWith(['product', 'warehouse']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'warehouse_id' => $this->warehouse_id,
            'product_id' => $this->product_id,
            'qty' => $this->qty,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andWhere(['like', 'lower(product.name)', strtolower($this->product_name)]);
        $query->andWhere(['like', 'lower(product.code)', strtolower($this->product_code)]);

        return $dataProvider;
    }
}
