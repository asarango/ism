<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisFaltas;

/**
 * ScholarisFaltasSearch represents the model behind the search form of `backend\models\ScholarisFaltas`.
 */
class ScholarisFaltasSearch extends ScholarisFaltas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholaris_perido_id', 'student_id'], 'integer'],
            [['fecha', 'fecha_solicitud_justificacion', 'motivo_justificacion', 'fecha_justificacion', 'respuesta_justificacion', 'usuario_justifica', 'created', 'created_at', 'updated', 'updated_at'], 'safe'],
            [['es_justificado'], 'boolean'],
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
        $query = ScholarisFaltas::find();

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
            'scholaris_perido_id' => $this->scholaris_perido_id,
            'student_id' => $this->student_id,
            'fecha' => $this->fecha,
            'fecha_solicitud_justificacion' => $this->fecha_solicitud_justificacion,
            'es_justificado' => $this->es_justificado,
            'fecha_justificacion' => $this->fecha_justificacion,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'motivo_justificacion', $this->motivo_justificacion])
            ->andFilterWhere(['ilike', 'respuesta_justificacion', $this->respuesta_justificacion])
            ->andFilterWhere(['ilike', 'usuario_justifica', $this->usuario_justifica])
            ->andFilterWhere(['ilike', 'created_at', $this->created_at])
            ->andFilterWhere(['ilike', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
