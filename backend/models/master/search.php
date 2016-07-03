<?php

namespace backend\models\master;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\master\UserNotification;

/**
 * search represents the model behind the search form about `backend\models\master\UserNotification`.
 */
class search extends UserNotification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'id', 'start_at', 'finish_at'], 'integer'],
            [['message'], 'safe'],
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
        $query = UserNotification::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'id' => $this->id,
            'start_at' => $this->start_at,
            'finish_at' => $this->finish_at,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
