<?php

namespace backend\models\accounting\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\accounting\PaymentMethod as PaymentMethodModel;

/**
 * PaymentMethod represents the model behind the search form about `backend\models\accounting\PaymentMethod`.
 */
class PaymentMethod extends PaymentMethodModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'branch_id', 'coa_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['method','coa_name'], 'safe'],
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
        $query = PaymentMethodModel::find();

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
            'branch_id' => $this->branch_id,
            'coa_id' => $this->coa_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'method', $this->method]);

        return $dataProvider;
    }
}
