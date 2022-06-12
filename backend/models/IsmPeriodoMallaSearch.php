<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmPeriodoMalla;

/**
 * IsmPeriodoMallaSearch represents the model behind the search form of `backend\models\IsmPeriodoMalla`.
 */
class IsmPeriodoMallaSearch extends IsmPeriodoMalla
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'malla_id', 'scholaris_periodo_id'], 'integer'],
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
        $query = IsmPeriodoMalla::find();

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
            'malla_id' => $this->malla_id,
            'scholaris_periodo_id' => $this->scholaris_periodo_id,
        ]);

        return $dataProvider;
    }
}
