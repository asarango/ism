<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TocPlanUnidadDetalle;

/**
 * TocPlanUnidadDetalleSearch represents the model behind the search form of `backend\models\TocPlanUnidadDetalle`.
 */
class TocPlanUnidadDetalleSearch extends TocPlanUnidadDetalle
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toc_plan_unidad_id'], 'integer'],
            [['evaluacion_pd', 'descripcion_unidad', 'preguntas_conocimiento', 'conocimientos_esenciales', 'actividades_principales', 'enfoques_aprendizaje', 'funciono_bien', 'no_funciono_bien', 'observaciones', 'created', 'created_at', 'updated', 'updated_at'], 'safe'],
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
        $query = TocPlanUnidadDetalle::find();

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
            'toc_plan_unidad_id' => $this->toc_plan_unidad_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'evaluacion_pd', $this->evaluacion_pd])
            ->andFilterWhere(['ilike', 'descripcion_unidad', $this->descripcion_unidad])
            ->andFilterWhere(['ilike', 'preguntas_conocimiento', $this->preguntas_conocimiento])
            ->andFilterWhere(['ilike', 'conocimientos_esenciales', $this->conocimientos_esenciales])
            ->andFilterWhere(['ilike', 'actividades_principales', $this->actividades_principales])
            ->andFilterWhere(['ilike', 'enfoques_aprendizaje', $this->enfoques_aprendizaje])
            ->andFilterWhere(['ilike', 'funciono_bien', $this->funciono_bien])
            ->andFilterWhere(['ilike', 'no_funciono_bien', $this->no_funciono_bien])
            ->andFilterWhere(['ilike', 'observaciones', $this->observaciones])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'updated', $this->updated]);

        return $dataProvider;
    }
}
