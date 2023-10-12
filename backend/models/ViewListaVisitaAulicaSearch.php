<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ViewListaVisitaAulica;

/**
 * ViewListaVisitaAulicaSearch represents the model behind the search form of `backend\models\ViewListaVisitaAulica`.
 */
class ViewListaVisitaAulicaSearch extends ViewListaVisitaAulica
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'periodo_id', 'total'], 'integer'],
            [['curso', 'paralelo', 'docente', 'materia', 'usuario_dece', 'periodo_codigo'], 'safe'],
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
    public function search($params, $usuarioPsicologo, $periodoId)
    {
        $query = ViewListaVisitaAulica::find()
                ->where([
                    'periodo_id' => $periodoId,
                    'usuario_dece' => $usuarioPsicologo
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
            'clase_id' => $this->clase_id,
            'periodo_id' => $this->periodo_id,
            'total' => $this->total,
        ]);

        $query->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'docente', $this->docente])
            ->andFilterWhere(['ilike', 'materia', $this->materia])
            ->andFilterWhere(['ilike', 'usuario_dece', $this->usuario_dece])
            ->andFilterWhere(['ilike', 'periodo_codigo', $this->periodo_codigo]);

        return $dataProvider;
    }
}
