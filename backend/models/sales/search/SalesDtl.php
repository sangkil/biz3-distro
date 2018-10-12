<?php

namespace backend\models\sales\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\sales\SalesDtl as SalesDtlModel;

/**
 * SalesDtl represents the model behind the search form about `backend\models\sales\SalesDtl`.
 */
class SalesDtl extends SalesDtlModel {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sales_id', 'product_id', 'uom_id'], 'integer'],
            [['qty', 'price', 'total_release', 'cogs', 'discount', 'tax'], 'number'],
            [['FrDate', 'ToDate', 'fr_date', 'to_date','branch_id'], 'safe']
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
        $query = SalesDtlModel::find();
        $query->with(['sales', 'product', 'uom']);
        $query->joinWith(['sales', 'product']);
        $query->orderBy('product.name');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['between', 'sales.date', $this->fr_date, $this->to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'sales_id' => $this->sales_id,
            'product_id' => $this->product_id,
            'uom_id' => $this->uom_id,
            'qty' => $this->qty,
            'price' => $this->price,
            'total_release' => $this->total_release,
            'cogs' => $this->cogs,
            'discount' => $this->discount,
            'tax' => $this->tax,
        ]);
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByProduct($params) {
        $query = SalesDtlModel::find();
        $query->select(['sales.date sdate', 
            'to_char(date, \'Day\') hari', 
            'product.name pname', 
            'product.category_id ctgr', 
            'sum(sales_dtl.qty) qty', 
            'sales_dtl.price', 
            'sum(sales_dtl.discount*sales_dtl.price*sales_dtl.qty/100) disc',
            'array_to_string(array_agg(sales.number), \', \') faktur']);

        //$query->with(['sales', 'product', 'uom']);
        $query->leftJoin('sales', 'sales_dtl.sales_id=sales.id');
        $query->leftJoin('product', 'sales_dtl.product_id=product.id');
        $query->groupBy(['product.name', 'sales.date', 'price', 'product.category_id']);
        $query->orderBy('sales.date', 'product.name');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['between', 'sales.date', $this->fr_date, $this->to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'sales_id' => $this->sales_id,
            'product_id' => $this->product_id,
            'uom_id' => $this->uom_id,
            'qty' => $this->qty,
            'price' => $this->price,
            'total_release' => $this->total_release,
            'cogs' => $this->cogs,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'sales.branch_id'=>  $this->branch_id
        ]);
        return $dataProvider;
    }
    
        /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByProductGroup($params) {
        $query = SalesDtlModel::find();
        $query->With(['product.group']);
        $query->select([
            'group.name group', 
            'sum(sales_dtl.qty * sales_dtl.price) amount', 
            'sum(sales_dtl.discount*sales_dtl.price*sales_dtl.qty/100) disc']);

        //$query->with(['sales', 'product', 'uom']);
        $query->leftJoin('sales', 'sales_dtl.sales_id=sales.id');
        $query->leftJoin('product', 'sales_dtl.product_id=product.id');
        $query->leftJoin('group', 'product.group_id=group.group_id');
        $query->groupBy(['product.group_id']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['between', 'sales.date', $this->fr_date, $this->to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'sales_id' => $this->sales_id,
            'product_id' => $this->product_id,
            'uom_id' => $this->uom_id,
            'qty' => $this->qty,
            'price' => $this->price,
            'total_release' => $this->total_release,
            'cogs' => $this->cogs,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'sales.branch_id'=>  $this->branch_id
        ]);
        return $dataProvider;
    }

}
