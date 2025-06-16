<?php

namespace app\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payments;

/**
 * PaymentSearch represents the model behind the search form of `app\models\Payments`.
 */
class PaymentSearch extends Payments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_id', 'order_id'], 'integer'],
            [['payment_method', 'payment_status', 'payment_date', 'cardholder_name', 'last_four_digits', 'expiry_month', 'expiry_year'], 'safe'],
            [['amount'], 'number'],
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
        $query = Payments::find();

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
            'payment_id' => $this->payment_id,
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
        ]);

        $query->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'cardholder_name', $this->cardholder_name])
            ->andFilterWhere(['like', 'last_four_digits', $this->last_four_digits])
            ->andFilterWhere(['like', 'expiry_month', $this->expiry_month])
            ->andFilterWhere(['like', 'expiry_year', $this->expiry_year]);

        return $dataProvider;
    }
}
