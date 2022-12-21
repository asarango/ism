<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IsmContenidoPlanInterdiciplinar;

/**
 * IsmContenidoPlanInterdiciplinarSearch represents the model behind the search form of `backend\models\IsmContenidoPlanInterdiciplinar`.
 */
class IsmContenidoPlanInterdiciplinarSearch extends IsmContenidoPlanInterdiciplinar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_seccion_interdiciplinar'], 'integer'],
            [['nombre_campo'], 'safe'],
            [['activo', 'heredado'], 'boolean'],
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
        $query = IsmContenidoPlanInterdiciplinar::find();

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
            'id_seccion_interdiciplinar' => $this->id_seccion_interdiciplinar,
            'activo' => $this->activo,
            'heredado' => $this->heredado,
        ]);

        $query->andFilterWhere(['ilike', 'nombre_campo', $this->nombre_campo]);

        return $dataProvider;
    }
}
