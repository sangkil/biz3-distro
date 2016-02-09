<?php

namespace backend\models\accounting\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\accounting\Payment as PaymentModel;

/**
 * Payment represents the model behind the search form about `backend\models\accounting\Payment`.
 */
class Payment extends PaymentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'payment_type', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date'], 'safe'],
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
        $query = PaymentModel::find();

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
            'date' => $this->date,
            'type' => $this->type,
            'payment_type' => $this->payment_type,
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
