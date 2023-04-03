<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceSeguimientoAcuerdos;

/**
 * DeceSeguimientoAcuerdosSearch represents the model behind the search form of `backend\models\DeceSeguimientoAcuerdos`.
 */
class DeceSeguimientoAcuerdosSearch extends DeceSeguimientoAcuerdos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_reg_seguimiento', 'secuencial'], 'integer'],
            [['acuerdo', 'responsable', 'fecha_max_cumplimiento'], 'safe'],
            [['cumplio'], 'boolean'],
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
        $query = DeceSeguimientoAcuerdos::find();

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
            'id_reg_seguimiento' => $this->id_reg_seguimiento,
            'secuencial' => $this->secuencial,
            'fecha_max_cumplimiento' => $this->fecha_max_cumplimiento,
            'cumplio' => $this->cumplio,
        ]);

        $query->andFilterWhere(['ilike', 'acuerdo', $this->acuerdo])
            ->andFilterWhere(['ilike', 'responsable', $this->responsable]);

        return $dataProvider;
    }
}
