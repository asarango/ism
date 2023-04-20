<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisAsistenciaAlumnosNovedades;

/**
 * ScholarisAsistenciaAlumnosNovedadesSearch represents the model behind the search form of `backend\models\ScholarisAsistenciaAlumnosNovedades`.
 */
class ViewNovedadesEstudianteSearch extends ViewNovedadesEstudiante
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {          
        return [
            [['id', 'curso_id', 'paralelo_id', 'scholaris_periodo_id'], 'integer'],
            [['fecha', 'nombre', 'solicitud_representante_user_id', 'solicitud_representante_fecha',        
            'curso', 'paralelo', 'materia', 'estudiante', 'codigo', 'observacion', 'docente',
            'solicitud_representante_motivo', 'justificacion_fecha', 'justificacion_usuario', 'acuerdo_justificacion'], 'safe'],
            [['es_justificado'], 'boolean'],
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
    public function search($params, $periodoId, $user)
    {
        $query = ViewNovedadesEstudiante::find()                
                ->where(['scholaris_periodo_id' => $periodoId, 'docente' => $user]);

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
            'fecha' => $this->fecha,
            'curso_id' => $this->curso_id,
            'paralelo_id' => $this->paralelo_id,
            'es_justificado' => $this->es_justificado,
            'solicitud_representante_fecha' => $this->solicitud_representante_fecha,
            'justificacion_fecha' => $this->justificacion_fecha,
            'justificacion_fecha' => $this->scholaris_periodo_id,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'materia', $this->materia])
            ->andFilterWhere(['ilike', 'estudiante', $this->estudiante])
            ->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'observacion', $this->observacion])
            ->andFilterWhere(['ilike', 'solicitud_representante_user_id', $this->solicitud_representante_user_id])
            ->andFilterWhere(['ilike', 'solicitud_representante_motivo', $this->solicitud_representante_motivo]);

        return $dataProvider;
    }
}
