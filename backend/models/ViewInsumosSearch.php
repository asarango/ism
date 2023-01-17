<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IsmAreaSearch represents the model behind the search form of `backend\models\IsmArea`.
 */
class ViewInsumosSearch extends ViewInsumos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'actividad_id', 'periodo_id', 'total_calificados', 'total_estudiantes', 'semana_numero'], 'integer'],
            [['curso', 'paralelo', 'nombre', 'nombre_nacional', 'inicio', 'title', 'login', 'bloque'], 'safe'],
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
    public function search($params, $user, $periodoId)
    {
        $query = ViewInsumos::find()
                ->where([
                    'login' => $user,
                    'periodo_id' => $periodoId
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
            'clase_id'          => $this->clase_id,
            'actividad_id'      => $this->actividad_id,
            'periodo_id'        => $this->periodo_id, 
            'total_calificados' => $this->total_calificados, 
            'total_estudiantes' => $this->total_estudiantes,
            'semana_numero' => $this->semana_numero
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
        
        ->andFilterWhere(['ilike', 'curso', $this->curso])
        ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
        ->andFilterWhere(['ilike', 'nombre', $this->nombre])
        ->andFilterWhere(['ilike', 'nombre_nacional', $this->nombre_nacional]) 
        ->andFilterWhere(['ilike', 'inicio', $this->inicio])
        ->andFilterWhere(['ilike', 'title', $this->title])
        ->andFilterWhere(['ilike', 'bloque', $this->bloque])
        ->andFilterWhere(['ilike', 'login', $this->login]);

        return $dataProvider;
    }
}
