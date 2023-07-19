<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BitacoraAprobacionesPlanificaciones;

/**
 * BitacoraAprobacionesPlanificacionesSearch represents the model behind the search form of `backend\models\BitacoraAprobacionesPlanificaciones`.
 */
class BitacoraAprobacionesPlanificacionesSearch extends BitacoraAprobacionesPlanificaciones
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['tipo_documento', 'link_pdf', 'fecha', 'estado', 'enviado_a', 'creado_por', 'fecha_creado', 'observaciones'], 'safe'],
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
        $query = BitacoraAprobacionesPlanificaciones::find();

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
            'fecha_creado' => $this->fecha_creado,
        ]);

        $query->andFilterWhere(['ilike', 'tipo_documento', $this->tipo_documento])
            ->andFilterWhere(['ilike', 'link_pdf', $this->link_pdf])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'enviado_a', $this->enviado_a])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
