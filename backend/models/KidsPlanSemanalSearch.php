<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KidsPlanSemanal;

/**
 * KidsPlanSemanalSearch represents the model behind the search form of `backend\models\KidsPlanSemanal`.
 */
class KidsPlanSemanalSearch extends KidsPlanSemanal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kids_unidad_micro_id', 'semana_id'], 'integer'],
            [['created_at', 'created', 'estado', 'sent_at', 'sent_by', 'approved_at', 'approved_by'], 'safe'],
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
        $query = KidsPlanSemanal::find();

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
            'kids_unidad_micro_id' => $this->kids_unidad_micro_id,
            'semana_id' => $this->semana_id,
            'created_at' => $this->created_at,
            'sent_at' => $this->sent_at,
            'approved_at' => $this->approved_at,
        ]);

        $query->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'sent_by', $this->sent_by])
            ->andFilterWhere(['ilike', 'approved_by', $this->approved_by]);

        return $dataProvider;
    }
}
