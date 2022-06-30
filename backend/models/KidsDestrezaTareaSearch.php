<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KidsDestrezaTarea;

/**
 * KidsDestrezaTareaSearch represents the model behind the search form of `backend\models\KidsDestrezaTarea`.
 */
class KidsDestrezaTareaSearch extends KidsDestrezaTarea
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plan_destreza_id'], 'integer'],
            [['fecha_presentacion', 'detalle_tarea', 'materiales', 'created_at', 'created', 'updated_at', 'upated'], 'safe'],
            [['publicado_al_estudiante'], 'boolean'],
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
        $query = KidsDestrezaTarea::find();

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
            'plan_destreza_id' => $this->plan_destreza_id,
            'fecha_presentacion' => $this->fecha_presentacion,
            'publicado_al_estudiante' => $this->publicado_al_estudiante,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'detalle_tarea', $this->detalle_tarea])
            ->andFilterWhere(['ilike', 'materiales', $this->materiales])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'upated', $this->upated]);

        return $dataProvider;
    }
}
