<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmGrupoPlanInterdiciplinar;

/**
 * IsmGrupoPlanInterdiciplinarSearch represents the model behind the search form of `backend\models\IsmGrupoPlanInterdiciplinar`.
 */
class IsmGrupoPlanInterdiciplinarSearch extends IsmGrupoPlanInterdiciplinar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_bloque', 'id_op_course', 'id_periodo'], 'integer'],
            [['nombre_grupo', 'created_at', 'created', 'updated_at', 'updated'], 'safe'],
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
        $query = IsmGrupoPlanInterdiciplinar::find();

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
            'id_bloque' => $this->id_bloque,
            'id_op_course' => $this->id_op_course,
            'id_periodo' => $this->id_periodo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'nombre_grupo', $this->nombre_grupo])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'updated', $this->updated]);

        return $dataProvider;
    }
}
