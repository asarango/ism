<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceDerivacion;

/**
 * DeceDerivacionSearch represents the model behind the search form of `backend\models\DeceDerivacion`.
 */
class DeceDerivacionSearch extends DeceDerivacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_estudiante'], 'integer'],
            [['tipo_derivacion', 'nombre_quien_deriva', 'fecha_derivacion', 'motivo_referencia', 
              'historia_situacion_actual', 'accion_desarrollada', 
              'tipo_ayuda', 'cargo_quien_deriva'], 'safe'],
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
        $query = DeceDerivacion::find();

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
            'fecha_derivacion' => $this->fecha_derivacion,
        ]);

        $query->andFilterWhere(['ilike', 'tipo_derivacion', $this->tipo_derivacion])
            ->andFilterWhere(['ilike', 'nombre_quien_deriva', $this->nombre_quien_deriva])
            ->andFilterWhere(['ilike', 'motivo_referencia', $this->motivo_referencia])
            ->andFilterWhere(['ilike', 'historia_situacion_actual', $this->historia_situacion_actual])
            ->andFilterWhere(['ilike', 'accion_desarrollada', $this->accion_desarrollada])
            ->andFilterWhere(['ilike', 'cargo_quien_deriva', $this->cargo_quien_deriva])
            ->andFilterWhere(['ilike', 'tipo_ayuda', $this->tipo_ayuda]);

        return $dataProvider;
    }
}
