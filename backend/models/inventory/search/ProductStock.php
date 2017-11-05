<?php

namespace backend\models\inventory\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\inventory\ProductStock as ProductStockModel;

/**
 * ProductStock represents the model behind the search form about `backend\models\inventory\ProductStock`.
 */
class ProductStock extends ProductStockModel {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['warehouse_id', 'product_id', 'qty', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['product_name', 'product_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = ProductStockModel::find();
        $query->select(['product_stock.*', 'product.*', 'warehouse.*']);
        $query->with(['warehouse', 'product.group', 'cogs']);
        $query->joinWith(['product', 'warehouse']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['warehouse_id'=>SORT_ASC, 'product.edition' => SORT_ASC]]
        ]);
        
        $dataProvider->sort->attributes['product.edition'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['product.edition' => SORT_ASC],
            'desc' => ['product.edition' => SORT_DESC],
        ];
        
//        $dataProvider->sort->attributes['product_stock.warehouse_id'] = [
//            // The tables are the ones our relation are configured to
//            // in my case they are prefixed with "tbl_"
//            'asc' => ['product_stock.warehouse_id' => SORT_ASC],
//            'desc' => ['product_stock.warehouse_id' => SORT_DESC],
//        ];

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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function artikel_grouped($params) {
        $query = ProductStockModel::find();
        $query->select(['product_stock.warehouse_id', 'TRIM(split_part(product."name", \';\', 2)) artikel', 'warehouse.id whse_id', 'warehouse.name whse_name', 'sum(product_stock.qty) jml']);
        $query->sum('product_stock.qty');
        $query->joinWith(['product', 'warehouse']);
        $query->groupBy(['product_stock.warehouse_id', 'TRIM(split_part(product."name", \';\', 2))', 'warehouse.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['warehouse_id' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'warehouse_id' => $this->warehouse_id,
            'qty' => $this->qty,
        ]);

        //echo $query->createCommand()->sql;
        return $dataProvider;
    }

}
