<?php

namespace app\models\master\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\master\U2Branch as U2BranchModel;

/**
 * U2Branch represents the model behind the search form about `app\models\master\U2Branch`.
 */
class U2Branch extends U2BranchModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'user_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
        $query = U2BranchModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'branch_id' => $this->branch_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
