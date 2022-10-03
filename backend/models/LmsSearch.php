<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Lms;

/**
 * LmsSearch represents the model behind the search form of `backend\models\Lms`.
 */
class LmsSearch extends Lms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ism_area_materia_id', 'tipo_bloque_comparte_valor', 'semana_numero', 'hora_numero'], 'integer'],
            [['tipo_recurso', 'titulo', 'indicaciones', 'fecha_aprobacion', 'userio_aprobo', 'created', 'created_at', 'updated', 'updated_at'], 'safe'],
            [['publicar', 'estado_activo', 'es_aprobado'], 'boolean'],
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
        $query = Lms::find();

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
            'ism_area_materia_id' => $this->ism_area_materia_id,
            'tipo_bloque_comparte_valor' => $this->tipo_bloque_comparte_valor,
            'semana_numero' => $this->semana_numero,
            'hora_numero' => $this->hora_numero,
            'publicar' => $this->publicar,
            'estado_activo' => $this->estado_activo,
            'es_aprobado' => $this->es_aprobado,
            'fecha_aprobacion' => $this->fecha_aprobacion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'tipo_recurso', $this->tipo_recurso])
            ->andFilterWhere(['ilike', 'titulo', $this->titulo])
            ->andFilterWhere(['ilike', 'indicaciones', $this->indicaciones])
            ->andFilterWhere(['ilike', 'userio_aprobo', $this->userio_aprobo])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'updated', $this->updated]);

        return $dataProvider;
    }
}
