<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmMallaArea;

/**
 * IsmMallaAreaSearch represents the model behind the search form of `backend\models\IsmMallaArea`.
 */
class IsmMallaAreaSearch extends IsmMallaArea
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'area_id', 'periodo_malla_id', 'orden'], 'integer'],
            [['promedia', 'imprime_libreta', 'es_cuantitativa'], 'boolean'],
            [['tipo'], 'safe'],
            [['porcentaje'], 'number'],
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
        $query = IsmMallaArea::find();

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
            'area_id' => $this->area_id,
            'periodo_malla_id' => $this->periodo_malla_id,
            'promedia' => $this->promedia,
            'imprime_libreta' => $this->imprime_libreta,
            'es_cuantitativa' => $this->es_cuantitativa,
            'porcentaje' => $this->porcentaje,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
