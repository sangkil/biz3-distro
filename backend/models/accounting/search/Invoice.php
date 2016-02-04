<?php

namespace backend\models\accounting\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\accounting\Invoice as InvoiceModel;

/**
 * Invoice represents the model behind the search form about `backend\models\accounting\Invoice`.
 */
class Invoice extends InvoiceModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'vendor_id', 'reff_type', 'reff_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date', 'due_date', 'description', 'tax_type'], 'safe'],
            [['value', 'tax_value'], 'number'],
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
        $query = InvoiceModel::find();

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
            'due_date' => $this->due_date,
            'type' => $this->type,
            'vendor_id' => $this->vendor_id,
            'reff_type' => $this->reff_type,
            'reff_id' => $this->reff_id,
            'status' => $this->status,
            'value' => $this->value,
            'tax_value' => $this->tax_value,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tax_type', $this->tax_type]);

        return $dataProvider;
    }
}
