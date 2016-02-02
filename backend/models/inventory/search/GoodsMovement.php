<?php

namespace backend\models\inventory\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\inventory\GoodsMovement as GoodsMovementModel;

/**
 * GoodsMovement represents the model behind the search form about `backend\models\inventory\GoodsMovement`.
 */
class GoodsMovement extends GoodsMovementModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'warehouse_id', 'type', 'reff_type', 'reff_id', 'vendor_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date', 'description'], 'safe'],
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
        $query = GoodsMovementModel::find();

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
            'warehouse_id' => $this->warehouse_id,
            'date' => $this->date,
            'type' => $this->type,
            'reff_type' => $this->reff_type,
            'reff_id' => $this->reff_id,
            'vendor_id' => $this->vendor_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
