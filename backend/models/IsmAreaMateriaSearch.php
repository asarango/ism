<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmAreaMateria;

/**
 * IsmAreaMateriaSearch represents the model behind the search form of `backend\models\IsmAreaMateria`.
 */
class IsmAreaMateriaSearch extends IsmAreaMateria
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'malla_area_id', 'materia_id', 'asignatura_curriculo_id', 'curso_curriculo_id', 'orden', 'total_horas_semana'], 'integer'],
            [['promedia', 'imprime_libreta', 'es_cuantitativa', 'es_bi'], 'boolean'],
            [['porcentaje'], 'number'],
            [['tipo', 'idioma'], 'safe'],
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
        $query = IsmAreaMateria::find();

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
            'malla_area_id' => $this->malla_area_id,
            'materia_id' => $this->materia_id,
            'promedia' => $this->promedia,
            'porcentaje' => $this->porcentaje,
            'imprime_libreta' => $this->imprime_libreta,
            'es_cuantitativa' => $this->es_cuantitativa,
            'asignatura_curriculo_id' => $this->asignatura_curriculo_id,
            'curso_curriculo_id' => $this->curso_curriculo_id,
            'orden' => $this->orden,
            'total_horas_semana' => $this->total_horas_semana,
            'es_bi' => $this->es_bi
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo])
                ->andFilterWhere(['ilike', 'idioma', $this->idioma]);

        return $dataProvider;
    }
}
