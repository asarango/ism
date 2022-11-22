<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MessageGroup;

/**
 * MessageGroupSearch represents the model behind the search form of `backend\models\MessageGroup`.
 */
class MessageGroupSearch extends MessageGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholaris_periodo_id', 'source_id'], 'integer'],
            [['source_table', 'nombre', 'tipo'], 'safe'],
            [['estado'], 'boolean'],
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
        $query = MessageGroup::find();

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
            'scholaris_periodo_id' => $this->scholaris_periodo_id,
            'source_id' => $this->source_id,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['ilike', 'source_table', $this->source_table])
            ->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
