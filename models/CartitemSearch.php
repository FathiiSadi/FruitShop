<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cartitem;

/**
 * CartitemSearch represents the model behind the search form of `app\models\Cartitem`.
 */
class CartitemSearch extends Cartitem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id', 'id', 'Quantity'], 'integer'],
            [['Price'], 'number'],
            [['added_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Cartitem::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id' => $this->id,
            'id' => $this->id,
            'Quantity' => $this->Quantity,
            'Price' => $this->Price,
            'added_at' => $this->added_at,
        ]);

        return $dataProvider;
    }
}
