<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KidsPlanSemanalHoraClase;

/**
 * KidsPlanSemanalHoraClaseSearch represents the model behind the search form of `backend\models\KidsPlanSemanalHoraClase`.
 */
class KidsPlanSemanalHoraClaseSearch extends KidsPlanSemanalHoraClase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plan_semanal_id', 'clase_id', 'detalle_id'], 'integer'],
            [['fecha', 'created_at', 'created'], 'safe'],
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
        $query = KidsPlanSemanalHoraClase::find();

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
            'plan_semanal_id' => $this->plan_semanal_id,
            'clase_id' => $this->clase_id,
            'detalle_id' => $this->detalle_id,
            'fecha' => $this->fecha,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'created', $this->created]);

        return $dataProvider;
    }
}
