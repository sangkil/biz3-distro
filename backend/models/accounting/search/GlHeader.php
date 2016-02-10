<?php

namespace backend\models\accounting\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\accounting\GlHeader as GlHeaderModel;

/**
 * GlHeader represents the model behind the search form about `backend\models\accounting\GlHeader`.
 */
class GlHeader extends GlHeaderModel {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'periode_id', 'branch_id', 'reff_type', 'reff_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date', 'description'], 'safe'],
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
        $query = GlHeaderModel::find();

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
            'periode_id' => $this->periode_id,
            'branch_id' => $this->branch_id,
            'reff_type' => $this->reff_type,
            'reff_id' => $this->reff_id,
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchDtl($params) {
        $query = GlHeaderModel::find();
        //$query->select(['gl_header.*','gl_detail.*']);
        $query->with(['glDetails','glDetails.coa']);
        //$query->joinWith(['glDetails']);
        $query->orderBy(['number'=>SORT_ASC]);
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
            'periode_id' => $this->periode_id,
            'branch_id' => $this->branch_id,
            'reff_type' => $this->reff_type,
            'reff_id' => $this->reff_id,
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
