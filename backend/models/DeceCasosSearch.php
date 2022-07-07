<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceCasos;

/**
 * DeceCasosSearch represents the model behind the search form of `backend\models\DeceCasos`.
 */
class DeceCasosSearch extends DeceCasos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numero_caso', 'id_estudiante', 'id_periodo', 'id_usuario'], 'integer'],
            [['estado', 'fecha_inicio', 'fecha_fin', 'detalle'], 'safe'],
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
        $query = DeceCasos::find();

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
            'numero_caso' => $this->numero_caso,
            'id_estudiante' => $this->id_estudiante,
            'id_periodo' => $this->id_periodo,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_usuario' => $this->id_usuario,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}
