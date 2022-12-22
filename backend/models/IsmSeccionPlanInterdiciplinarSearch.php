<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmSeccionPlanInterdiciplinar;

/**
 * IsmSeccionPlanInterdiciplinarSearch represents the model behind the search form of `backend\models\IsmSeccionPlanInterdiciplinar`.
 */
class IsmSeccionPlanInterdiciplinarSearch extends IsmSeccionPlanInterdiciplinar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'num_seccion'], 'integer'],
            [['nombre_seccion'], 'safe'],
            [['activo'], 'boolean'],
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
        $query = IsmSeccionPlanInterdiciplinar::find();

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
            'num_seccion' => $this->num_seccion,
            'activo' => $this->activo,
        ]);

        $query->andFilterWhere(['ilike', 'nombre_seccion', $this->nombre_seccion]);

        return $dataProvider;
    }
}
