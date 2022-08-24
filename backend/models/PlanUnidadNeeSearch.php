<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanUnidadNee;

/**
 * PlanUnidadNeeSearch represents the model behind the search form of `backend\models\PlanUnidadNee`.
 */
class PlanUnidadNeeSearch extends PlanUnidadNee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nee_x_unidad_id', 'curriculo_bloque_unidad_id'], 'integer'],
            [['destrezas', 'actividades', 'recursos', 'indicadores_evaluacion', 'tecnicas_instrumentos', 'detalle_pai_dip'], 'safe'],
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
        $query = PlanUnidadNee::find();

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
            'nee_x_unidad_id' => $this->nee_x_unidad_id,
            'curriculo_bloque_unidad_id' => $this->curriculo_bloque_unidad_id,
        ]);

        $query->andFilterWhere(['ilike', 'destrezas', $this->destrezas])
            ->andFilterWhere(['ilike', 'actividades', $this->actividades])
            ->andFilterWhere(['ilike', 'recursos', $this->recursos])
            ->andFilterWhere(['ilike', 'indicadores_evaluacion', $this->indicadores_evaluacion])
            ->andFilterWhere(['ilike', 'tecnicas_instrumentos', $this->tecnicas_instrumentos])
            ->andFilterWhere(['ilike', 'detalle_pai_dip', $this->detalle_pai_dip]);

        return $dataProvider;
    }
}
