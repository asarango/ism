<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceIntervencionCompromiso;

/**
 * DeceIntervencionCompromisoSearch represents the model behind the search form of `backend\models\DeceIntervencionCompromiso`.
 */
class DeceIntervencionCompromisoSearch extends DeceIntervencionCompromiso
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_dece_intervencion'], 'integer'],
            [['comp_estudiante', 'comp_representante', 'comp_docente', 'comp_dece', 'fecha_max_cumplimiento', 'revision_compromiso'], 'safe'],
            [['esaprobado'], 'boolean'],
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
        $query = DeceIntervencionCompromiso::find();

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
            'id_dece_intervencion' => $this->id_dece_intervencion,
            'fecha_max_cumplimiento' => $this->fecha_max_cumplimiento,
            'esaprobado' => $this->esaprobado,
        ]);

        $query->andFilterWhere(['ilike', 'comp_estudiante', $this->comp_estudiante])
            ->andFilterWhere(['ilike', 'comp_representante', $this->comp_representante])
            ->andFilterWhere(['ilike', 'comp_docente', $this->comp_docente])
            ->andFilterWhere(['ilike', 'comp_dece', $this->comp_dece])
            ->andFilterWhere(['ilike', 'revision_compromiso', $this->revision_compromiso]);

        return $dataProvider;
    }
}
