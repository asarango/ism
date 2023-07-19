<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanificacionSemanal;

/**
 * PlanificacionSemanalSearch represents the model behind the search form of `backend\models\PlanificacionSemanal`.
 */
class PlanificacionSemanalSearch extends PlanificacionSemanal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'semana_id', 'clase_id', 'hora_id', 'orden_hora_semana'], 'integer'],
            [['fecha', 'tema', 'actividades', 'diferenciacion_nee', 'recursos', 'created', 'created_at', 'updated', 'updated_at'], 'safe'],
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
        $query = PlanificacionSemanal::find();

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
            'semana_id' => $this->semana_id,
            'clase_id' => $this->clase_id,
            'fecha' => $this->fecha,
            'hora_id' => $this->hora_id,
            'orden_hora_semana' => $this->orden_hora_semana,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'tema', $this->tema])
            ->andFilterWhere(['ilike', 'actividades', $this->actividades])
            ->andFilterWhere(['ilike', 'diferenciacion_nee', $this->diferenciacion_nee])
            ->andFilterWhere(['ilike', 'recursos', $this->recursos])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'updated', $this->updated]);

        return $dataProvider;
    }
}
