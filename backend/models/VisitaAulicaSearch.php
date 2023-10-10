<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\VisitaAulica;

/**
 * VisitaAulicaSearch represents the model behind the search form of `backend\models\VisitaAulica`.
 */
class VisitaAulicaSearch extends VisitaAulica
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'estudiantes_asistidos'], 'integer'],
            [['aplica_grupal'], 'boolean'],
            [['psicologo_usuario', 'fecha', 'hora_inicio', 'hora_finalizacion', 'observaciones_al_docente', 'fecha_firma_dece', 'fecha_firma_docente'], 'safe'],
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
        $query = VisitaAulica::find();

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
            'clase_id' => $this->clase_id,
            'estudiantes_asistidos' => $this->estudiantes_asistidos,
            'aplica_grupal' => $this->aplica_grupal,
            'fecha' => $this->fecha,
            'hora_inicio' => $this->hora_inicio,
            'hora_finalizacion' => $this->hora_finalizacion,
            'fecha_firma_dece' => $this->fecha_firma_dece,
            'fecha_firma_docente' => $this->fecha_firma_docente,
        ]);

        $query->andFilterWhere(['ilike', 'psicologo_usuario', $this->psicologo_usuario])
            ->andFilterWhere(['ilike', 'observaciones_al_docente', $this->observaciones_al_docente]);

        return $dataProvider;
    }
}
