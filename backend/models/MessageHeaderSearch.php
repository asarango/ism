<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MessageHeader;

/**
 * MessageHeaderSearch represents the model behind the search form of `backend\models\MessageHeader`.
 */
class MessageHeaderSearch extends MessageHeader
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tabla_origen_id'], 'integer'],
            [['remite_usuario', 'created_at', 'updated_at', 'asunto', 'texto', 'aplicacion_origen', 'tabla_origen', 'estado'], 'safe'],
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
    public function search($params, $from)
    {
        $query = MessageHeader::find()
                ->where(['remite_usuario' => $from]);

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tabla_origen_id' => $this->tabla_origen_id,
        ]);

        $query->andFilterWhere(['ilike', 'remite_usuario', $this->remite_usuario])
            ->andFilterWhere(['ilike', 'asunto', $this->asunto])
            ->andFilterWhere(['ilike', 'texto', $this->texto])
            ->andFilterWhere(['ilike', 'aplicacion_origen', $this->aplicacion_origen])
            ->andFilterWhere(['ilike', 'tabla_origen', $this->tabla_origen]);

        return $dataProvider;
    }
}
