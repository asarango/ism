<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ViewActividadCrear;

/**
 * ViewActividadCrearSearch represents the model behind the search form of `backend\models\ViewActividadCrear`.
 */
class ViewActividadCrearSearch extends ViewActividadCrear
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plan_id'], 'integer'],
            [['curso', 'paralelo', 'trimestre', 'nombre_semana', 'fecha', 'hora', 'materia', 'tema', 'login'], 'safe'],
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
    public function search($params, $userLog)
    {
        $query = ViewActividadCrear::find()
        ->where(['login' => $userLog]);

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
            'plan_id' => $this->plan_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'trimestre', $this->trimestre])
            ->andFilterWhere(['ilike', 'nombre_semana', $this->nombre_semana])
            ->andFilterWhere(['ilike', 'hora', $this->hora])
            ->andFilterWhere(['ilike', 'materia', $this->materia])
            ->andFilterWhere(['ilike', 'tema', $this->tema])
            ->andFilterWhere(['ilike', 'login', $this->login]);

        return $dataProvider;
    }
}
