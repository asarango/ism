<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisAsistenciaAlumnosNovedades;

/**
 * ScholarisAsistenciaAlumnosNovedadesSearch represents the model behind the search form of `backend\models\ScholarisAsistenciaAlumnosNovedades`.
 */
class ScholarisAsistenciaAlumnosNovedadesSearch extends ScholarisAsistenciaAlumnosNovedades
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'integer'],
            [['observacion', 'codigo_justificacion', 'acuerdo_justificacion'], 'safe'],
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
    public function search($params)
    {
        $query = ScholarisAsistenciaAlumnosNovedades::find();

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
            'asistencia_profesor_id' => $this->asistencia_profesor_id,
            'comportamiento_detalle_id' => $this->comportamiento_detalle_id,
            'grupo_id' => $this->grupo_id,
            'es_justificado' => $this->es_justificado,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion])
            ->andFilterWhere(['ilike', 'codigo_justificacion', $this->codigo_justificacion])
            ->andFilterWhere(['ilike', 'acuerdo_justificacion', $this->acuerdo_justificacion]);

        return $dataProvider;
    }
}
