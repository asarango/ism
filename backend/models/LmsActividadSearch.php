<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LmsActividad;

/**
 * LmsActividadSearch represents the model behind the search form of `backend\models\LmsActividad`.
 */
class LmsActividadSearch extends LmsActividad
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lms_id', 'tipo_actividad_id'], 'integer'],
            [['titulo', 'descripcion', 'tarea', 'material_apoyo', 'retroalimentacion', 'created', 'created_at', 'updated', 'updated_at'], 'safe'],
            [['es_calificado', 'es_publicado', 'es_aprobado'], 'boolean'],
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
    public function search($params, $lmsId)
    {
        $query = LmsActividad::find()->where([
            'lms_id' => $lmsId
        ]);

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
            'lms_id' => $this->lms_id,
            'tipo_actividad_id' => $this->tipo_actividad_id,
            'es_calificado' => $this->es_calificado,
            'es_publicado' => $this->es_publicado,
            'es_aprobado' => $this->es_aprobado,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'titulo', $this->titulo])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'tarea', $this->tarea])
            ->andFilterWhere(['ilike', 'material_apoyo', $this->material_apoyo])
            ->andFilterWhere(['ilike', 'retroalimentacion', $this->retroalimentacion])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'updated', $this->updated]);

        return $dataProvider;
    }
}
