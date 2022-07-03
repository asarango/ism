<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DeceRegistroAgendamientoAtencion;

/**
 * DeceRegistroAgendamientoAtencionSearch represents the model behind the search form of `app\models\DeceRegistroAgendamientoAtencion`.
 */
class DeceRegistroAgendamientoAtencionSearch extends DeceRegistroAgendamientoAtencion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_reg_seguimiento'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'estado', 'pronunciamiento', 'acuerdo_y_compromiso', 'evidencia', 'path_archivo'], 'safe'],
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
        $query = DeceRegistroAgendamientoAtencion::find();

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
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'pronunciamiento', $this->pronunciamiento])
            ->andFilterWhere(['ilike', 'acuerdo_y_compromiso', $this->acuerdo_y_compromiso])
            ->andFilterWhere(['ilike', 'evidencia', $this->evidencia])
            ->andFilterWhere(['ilike', 'path_archivo', $this->path_archivo]);

        return $dataProvider;
    }
}
