<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FirmarDocumentos;

/**
 * FirmarDocumentosSearch represents the model behind the search form of `backend\models\FirmarDocumentos`.
 */
class FirmarDocumentosSearch extends FirmarDocumentos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'documento_id'], 'integer'],
            [['tabla_source', 'nombre', 'cargo', 'cedula', 'fecha_firma', 'tipo'], 'safe'],
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
        $query = FirmarDocumentos::find();

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
            'documento_id' => $this->documento_id,
            'fecha_firma' => $this->fecha_firma,
        ]);

        $query->andFilterWhere(['ilike', 'tabla_source', $this->tabla_source])
            ->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'cargo', $this->cargo])
            ->andFilterWhere(['ilike', 'cedula', $this->cedula])
            ->andFilterWhere(['ilike', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
