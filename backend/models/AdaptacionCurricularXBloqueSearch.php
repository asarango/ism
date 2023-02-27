<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AdaptacionCurricularXBloque;

/**
 * AdaptacionCurricularXBloqueSearch represents the model behind the search form of `backend\models\AdaptacionCurricularXBloque`.
 */
class AdaptacionCurricularXBloqueSearch extends AdaptacionCurricularXBloque
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_nee_x_clase', 'id_scholaris_bloque'], 'integer'],
            [['adaptacion_curricular', 'creado_por', 'fecha_creacion', 'actualizado_por', 'fecha_actualizacion'], 'safe'],
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
        $query = AdaptacionCurricularXBloque::find();

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
            'id_nee_x_clase' => $this->id_nee_x_clase,
            'id_scholaris_bloque' => $this->id_scholaris_bloque,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_actualizacion' => $this->fecha_actualizacion,
        ]);

        $query->andFilterWhere(['ilike', 'adaptacion_curricular', $this->adaptacion_curricular])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
