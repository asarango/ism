<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceSeguimientoFirmas;

/**
 * DeceSeguimientoFirmasSearch represents the model behind the search form of `backend\models\DeceSeguimientoFirmas`.
 */
class DeceSeguimientoFirmasSearch extends DeceSeguimientoFirmas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_reg_seguimiento', 'cedula'], 'integer'],
            [['nombre', 'parentesco', 'cargo'], 'safe'],
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
        $query = DeceSeguimientoFirmas::find();

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
            'cedula' => $this->cedula,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'parentesco', $this->parentesco])
            ->andFilterWhere(['ilike', 'cargo', $this->cargo]);

        return $dataProvider;
    }
}
