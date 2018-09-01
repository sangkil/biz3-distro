<?php

namespace backend\models\sales\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\sales\Sales as SalesModel;

/**
 * Sales represents the model behind the search form about `backend\models\sales\Sales`.
 */
class Sales extends SalesModel {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'vendor_id', 'branch_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date', 'Date'], 'safe'],
            [['value', 'discount'], 'number'],
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
        $query = SalesModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'branch_id' => $this->branch_id,
            'date' => $this->date,
            'value' => $this->value,
            'discount' => $this->discount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }

    public function searchByBranch($params) {        
        $query = SalesModel::find();
        $query->select(['branch_id', 'branch.name', 'sum(value) as value']);
        $query->joinWith(['branch']);
        $query->groupBy(['branch_id', 'branch.name']);
        $query->assigned();

        if (isset($params['Sales']['Date'])) {
            $query->andWhere("date_part('month',date)=:dmonth", [':dmonth' => $params['Sales']['Date']]);
        }else{    
            $query->andWhere("date_part('month',date)=:dmonth", [':dmonth' => (int) date('m')]);
            $query->andWhere("date_part('year',date)=:dyear", [':dyear' => (int) date('Y')]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'branch_id' => $this->branch_id,
            'date' => $this->date,
            'value' => $this->value,
            'discount' => $this->discount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }

    public function searchDaily($params) {
        $query = SalesModel::find();
        $query->select(['date', 'sum(value) as value']);
        $query->groupBy(['date']);
        $query->orderBy(['date' => SORT_ASC]);

        if (isset($params['Sales']['Date'])) {
            $query->andWhere("date_part('month',date)=:dmonth", [':dmonth' => $params['Sales']['Date']]);
            $query->andWhere("date_part('year',date)=:dyear", [':dyear' => date('Y')]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>['pagesize'=>31]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'branch_id' => $this->branch_id,
            'date' => $this->date,
            'value' => $this->value,
            'discount' => $this->discount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }

}
