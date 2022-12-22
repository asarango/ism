<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CurriculoMec;

/**
 * CurriculoMecSearch represents the model behind the search form of `backend\models\CurriculoMec`.
 */
class CurriculoMecSearch extends CurriculoMec
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'asignatura_id', 'subnivel_id', 'order_block'], 'integer'],
            [['reference_type', 'code', 'description', 'aux_1', 'aux_2', 'belongs_to'], 'safe'],
            [['is_essential'], 'boolean'],
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
        $query = CurriculoMec::find();

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
            'asignatura_id' => $this->asignatura_id,
            'subnivel_id' => $this->subnivel_id,
            'is_essential' => $this->is_essential,
            'order_block' => $this->order_block,
        ]);

        $query->andFilterWhere(['ilike', 'reference_type', $this->reference_type])
            ->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'aux_1', $this->aux_1])
            ->andFilterWhere(['ilike', 'aux_2', $this->aux_2])
            ->andFilterWhere(['ilike', 'belongs_to', $this->belongs_to]);

        return $dataProvider;
    }


    public function searchObjectGrado($params)
    {
        $query = CurriculoMec::find()
            ->where(['reference_type' => 'objgrado']);

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
            'asignatura_id' => $this->asignatura_id,
            'subnivel_id' => $this->subnivel_id,
            'is_essential' => $this->is_essential,
            'order_block' => $this->order_block,
        ]);

        $query->andFilterWhere(['ilike', 'reference_type', $this->reference_type])
            ->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'aux_1', $this->aux_1])
            ->andFilterWhere(['ilike', 'aux_2', $this->aux_2])
            ->andFilterWhere(['ilike', 'belongs_to', $this->belongs_to]);

        return $dataProvider;
    }
}
