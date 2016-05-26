<?php

namespace backend\models\sales\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\sales\Sales as SalesModel;

/**
 * Sales represents the model behind the search form about `backend\models\sales\Sales`.
 */
class Sales extends SalesModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vendor_id', 'branch_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date'], 'safe'],
            [['value', 'discount'], 'number'],
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
    
    public function searchByBranch($params)
    {
        $query = SalesModel::find();
        $query->select(['branch_id','branch.name', 'sum(value) as value']);
        $query->joinWith(['branch']);
        $query->groupBy(['branch_id','branch.name']);

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
}
