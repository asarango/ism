<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ViewStudiantesCv;

/**
 * ViewStudiantesCvSearch represents the model behind the search form of `backend\models\ViewStudiantesCv`.
 */
class ViewStudiantesCvSearch extends ViewStudiantesCv
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estudiante_id', 'inscription_id'], 'integer'],
            [['seccion', 'curso', 'paralelo', 'estudiante', 'inscription_state'], 'safe'],
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
        $query = ViewStudiantesCv::find();

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
            'estudiante_id' => $this->estudiante_id,
            'inscription_id' => $this->inscription_id,
        ]);

        $query->andFilterWhere(['ilike', 'seccion', $this->seccion])
            ->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'estudiante', $this->estudiante])
            ->andFilterWhere(['ilike', 'inscription_state', $this->inscription_state]);

        return $dataProvider;
    }
}
