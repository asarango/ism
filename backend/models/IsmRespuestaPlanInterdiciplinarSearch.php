<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmRespuestaPlanInterdiciplinar;

/**
 * IsmRespuestaPlanInterdiciplinarSearch represents the model behind the search form of `backend\models\IsmRespuestaPlanInterdiciplinar`.
 */
class IsmRespuestaPlanInterdiciplinarSearch extends IsmRespuestaPlanInterdiciplinar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_grupo_plan_inter', 'id_contenido_plan_inter'], 'integer'],
            [['respuesta'], 'safe'],
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
        $query = IsmRespuestaPlanInterdiciplinar::find();

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
            'id_grupo_plan_inter' => $this->id_grupo_plan_inter,
            'id_contenido_plan_inter' => $this->id_contenido_plan_inter,
        ]);

        $query->andFilterWhere(['ilike', 'respuesta', $this->respuesta]);

        return $dataProvider;
    }
}
