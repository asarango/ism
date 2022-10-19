<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceDeteccion;

/**
 * DeceDeteccionSearch represents the model behind the search form of `backend\models\DeceDeteccion`.
 */
class DeceDeteccionSearch extends DeceDeteccion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numero_deteccion', 'id_estudiante', 'id_caso', 'numero_caso'], 'integer'],
            [['nombre_estudiante', 'anio', 'paralelo', 'nombre_quien_reporta', 'cargo', 'cedula', 'fecha_reporte', 'descripcion_del_hecho', 'hora_aproximada', 'acciones_realizadas', 'lista_evidencias', 'path_archivos'], 'safe'],
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
        $query = DeceDeteccion::find();

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
            'numero_deteccion' => $this->numero_deteccion,
            'id_estudiante' => $this->id_estudiante,
            'id_caso' => $this->id_caso,
            'numero_caso' => $this->numero_caso,
            'fecha_reporte' => $this->fecha_reporte,
        ]);

        $query->andFilterWhere(['ilike', 'nombre_estudiante', $this->nombre_estudiante])
            ->andFilterWhere(['ilike', 'anio', $this->anio])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'nombre_quien_reporta', $this->nombre_quien_reporta])
            ->andFilterWhere(['ilike', 'cargo', $this->cargo])
            ->andFilterWhere(['ilike', 'cedula', $this->cedula])
            ->andFilterWhere(['ilike', 'descripcion_del_hecho', $this->descripcion_del_hecho])
            ->andFilterWhere(['ilike', 'hora_aproximada', $this->hora_aproximada])
            ->andFilterWhere(['ilike', 'acciones_realizadas', $this->acciones_realizadas])
            ->andFilterWhere(['ilike', 'lista_evidencias', $this->lista_evidencias])
            ->andFilterWhere(['ilike', 'path_archivos', $this->path_archivos]);

        return $dataProvider;
    }
}
