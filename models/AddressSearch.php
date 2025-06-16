<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Addresses;

/**
 * AddressSearch represents the model behind the search form of `app\models\Addresses`.
 */
class AddressSearch extends Addresses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_id', 'UserID', 'is_default'], 'integer'],
            [['recipient_name', 'street_address', 'city', 'state', 'postal_code', 'country', 'phone_number', 'created_at'], 'safe'],
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
        $query = Addresses::find();

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
            'address_id' => $this->address_id,
            'UserID' => $this->UserID,
            'is_default' => $this->is_default,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'recipient_name', $this->recipient_name])
            ->andFilterWhere(['like', 'street_address', $this->street_address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        return $dataProvider;
    }
}
