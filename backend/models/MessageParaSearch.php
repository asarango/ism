<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MessagePara;

/**
 * MessageParaSearch represents the model behind the search form of `backend\models\MessagePara`.
 */
class MessageParaSearch extends MessagePara
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'message_id'], 'integer'],
            [['para_usuario', 'estado', 'fecha_recepcion', 'fecha_lectura'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MessagePara::find();

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
            'id' => $this->id,
            'message_id' => $this->message_id,
            'fecha_recepcion' => $this->fecha_recepcion,
            'fecha_lectura' => $this->fecha_lectura,
        ]);

        $query->andFilterWhere(['ilike', 'para_usuario', $this->para_usuario])
            ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
