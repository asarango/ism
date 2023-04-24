<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IsmAreaSearch represents the model behind the search form of `backend\models\IsmArea`.
 */
class ViewFaltasSearch extends ViewFaltas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholaris_perido_id'], 'integer'],
            [['fecha_falta', 'fecha_solicitud_justificacion', 'motivo_justificacion', 'es_justificado', 'fecha_justificacion', 
                'respuesta_justificacion', 'usuario_justifica', 'created', 
                'created_at', 'updated', 'updated_at', 'student', 'solicita_justificacion'], 'safe'],
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
    public function search($params, $periodoId)
    {
        $query = ViewFaltas::find()
                ->where([
                    'scholaris_perido_id' => $periodoId
                ]);

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
            'scholaris_perido_id' => $this->scholaris_perido_id
        ]);

        $query->andFilterWhere(['ilike', 'fecha_falta', $this->fecha_falta])
        ->andFilterWhere(['ilike', 'student', $this->student])
        ->andFilterWhere(['ilike', 'fecha_solicitud_justificacion', $this->fecha_solicitud_justificacion])
        ->andFilterWhere(['ilike', 'motivo_justificacion', $this->motivo_justificacion])
        ->andFilterWhere(['ilike', 'es_justificado', $this->es_justificado])
        ->andFilterWhere(['ilike', 'solicita_justificacion', $this->solicita_justificacion])
        ->andFilterWhere(['ilike', 'fecha_justificacion', $this->fecha_justificacion])
        ->andFilterWhere(['ilike', 'respuesta_justificacion', $this->respuesta_justificacion])
        ->andFilterWhere(['ilike', 'usuario_justifica', $this->usuario_justifica])
        ->andFilterWhere(['ilike', 'created', $this->created])
        ->andFilterWhere(['ilike', 'created_at', $this->created_at])
        ->andFilterWhere(['ilike', 'updated', $this->updated])
        ->andFilterWhere(['ilike', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
