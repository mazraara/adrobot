<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Permission;

/**
 * PermissionSearch represents the model behind the search form about `app\models\Permission`.
 */
class PermissionSearch extends Permission
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category', 'createdAt', 'updatedAt'], 'safe'],
            [['createdById', 'updatedById'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // Bypass scenarios() implementation in the parent class
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
        $query = Permission::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'category', $this->category]);

        $query->orderBy('category');

        return $dataProvider;
    }
}
