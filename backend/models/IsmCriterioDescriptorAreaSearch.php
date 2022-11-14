<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmCriterioDescriptorArea;

/**
 * IsmCriterioDescriptorAreaSearch represents the model behind the search form of `backend\models\IsmCriterioDescriptorArea`.
 */
class IsmCriterioDescriptorAreaSearch extends IsmCriterioDescriptorArea
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_area', 'id_curso', 'id_criterio', 'id_literal_criterio', 'id_descriptor', 'id_literal_descriptor'], 'integer'],
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
        $query = IsmCriterioDescriptorArea::find();

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
            'id_area' => $this->id_area,
            'id_curso' => $this->id_curso,
            'id_criterio' => $this->id_criterio,
            'id_literal_criterio' => $this->id_literal_criterio,
            'id_descriptor' => $this->id_descriptor,
            'id_literal_descriptor' => $this->id_literal_descriptor,
        ]);

        return $dataProvider;
    }
}