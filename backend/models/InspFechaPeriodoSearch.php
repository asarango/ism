<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InspFechaPeriodo;

/**
 * InspFechaPeriodoSearch represents the model behind the search form of `backend\models\InspFechaPeriodo`.
 */
class InspFechaPeriodoSearch extends InspFechaPeriodo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'observacion'], 'safe'],
            [['periodo_id', 'numero_dia'], 'integer'],
            [['hay_asitencia', 'es_presencial'], 'boolean'],
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
    public function search($params, $periodoId)
    {
        $query = InspFechaPeriodo::find()
                ->where(['periodo_id' => $periodoId]);

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
            'fecha' => $this->fecha,
            'periodo_id' => $this->periodo_id,
            'numero_dia' => $this->numero_dia,
            'hay_asitencia' => $this->hay_asitencia,
            'es_presencial' => $this->es_presencial,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
