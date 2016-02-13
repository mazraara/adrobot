<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\State;

/**
 * StateSearch represents the model behind the search form about `app\models\State`.
 */
class StateSearch extends State
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id', 'country_id'], 'integer'],
            [['name', 'country_id', 'id'], 'safe'],
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
        $query = State::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'country_id',
            ]
        ]);

        $this->load($params);

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['country_id' => $this->country_id]);

        return $dataProvider;
    }
}
