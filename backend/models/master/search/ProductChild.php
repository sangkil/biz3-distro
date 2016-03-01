<?php

namespace backend\models\master\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\master\ProductChild as ProductChildModel;

/**
 * ProductChild represents the model behind the search form about `backend\models\master\ProductChild`.
 */
class ProductChild extends ProductChildModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barcode'], 'safe'],
            [['product_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
        $query = ProductChildModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'barcode', $this->barcode]);

        return $dataProvider;
    }
}
