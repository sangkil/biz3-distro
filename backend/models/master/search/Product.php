<?php

namespace backend\models\master\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\master\Product as ProductModel;

/**
 * Product represents the model behind the search form about `backend\models\master\Product`.
 */
class Product extends ProductModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'category_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code', 'name', 'edition','stockable','Edition'], 'safe'],
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
        $query = ProductModel::find();        
        $query->with(['category','group']);

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
            'group_id' => $this->group_id,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'lower(name)', strtolower($this->name)]);

        return $dataProvider;
    }
}
