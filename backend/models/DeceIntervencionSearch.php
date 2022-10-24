<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceIntervencion;

/**
 * DeceIntervencionSearch represents the model behind the search form of `backend\models\DeceIntervencion`.
 */
class DeceIntervencionSearch extends DeceIntervencion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_estudiante', 'id_area'], 'integer'],
            [['fecha_intervencion', 'razon', 'otra_area', 'acciones_responsables'], 'safe'],
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
        $query = DeceIntervencion::find();

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
            'id_estudiante' => $this->id_estudiante,
            'fecha_intervencion' => $this->fecha_intervencion,
            'id_area' => $this->id_area,
        ]);

        $query->andFilterWhere(['ilike', 'razon', $this->razon])
            ->andFilterWhere(['ilike', 'otra_area', $this->otra_area])
            ->andFilterWhere(['ilike', 'acciones_responsables', $this->acciones_responsables]);

        return $dataProvider;
    }
}
