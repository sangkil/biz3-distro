<?php

namespace backend\models\accounting\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\accounting\coa as coaModel;

/**
 * coa represents the model behind the search form about `backend\models\accounting\coa`.
 */
class coa extends coaModel {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'parent_id', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code', 'name', 'normal_balance'], 'safe'],
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
        $query = coaModel::find();
        //$query->hasParent();
        $query->codeOrdered();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
        ->andFilterWhere(['like', 'lower(name)', strtolower($this->name)])
        ->andFilterWhere(['like', 'lower(normal_balance)', strtolower($this->normal_balance)]);

        return $dataProvider;
    }

}
