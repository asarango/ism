<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceRegistroSeguimiento;

/**
 * DeceRegistroSeguimientoSearch represents the model behind the search form of `app\models\DeceRegistroSeguimiento`.
 */
class DeceRegistroSeguimientoSearch extends DeceRegistroSeguimiento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_clase', 'id_estudiante'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'estado', 'motivo', 'submotivo', 'submotivo2', 'persona_solicitante', 'atendido_por', 'atencion_para', 'responsable_seguimiento'], 'safe'],
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
        $query = DeceRegistroSeguimiento::find();

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
            'id_clase' => $this->id_clase,
            'id_estudiante' => $this->id_estudiante,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'motivo', $this->motivo])
            ->andFilterWhere(['ilike', 'submotivo', $this->submotivo])
            ->andFilterWhere(['ilike', 'submotivo2', $this->submotivo2])
            ->andFilterWhere(['ilike', 'persona_solicitante', $this->persona_solicitante])
            ->andFilterWhere(['ilike', 'atendido_por', $this->atendido_por])
            ->andFilterWhere(['ilike', 'atencion_para', $this->atencion_para])
            ->andFilterWhere(['ilike', 'responsable_seguimiento', $this->responsable_seguimiento]);

        return $dataProvider;
    }
}
