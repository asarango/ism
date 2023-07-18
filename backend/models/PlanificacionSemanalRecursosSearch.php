<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanificacionSemanalRecursos;

/**
 * PlanificacionSemanalRecursosSearch represents the model behind the search form of `backend\models\PlanificacionSemanalRecursos`.
 */
class PlanificacionSemanalRecursosSearch extends PlanificacionSemanalRecursos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plan_semanal_id'], 'integer'],
            [['tema', 'tipo_recurso', 'url_recurso'], 'safe'],
            [['estado'], 'boolean'],
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
    public function search($params,$usuarioLogin,$planificacionSemanalId)
    {
        $query = PlanificacionSemanalRecursos::find()
            ->select([
                'planificacion_semanal_recursos.tema',
                'planificacion_semanal_recursos.tipo_recurso',
                'planificacion_semanal_recursos.url_recurso',
                'planificacion_semanal_recursos.estado',
                'planificacion_semanal_recursos.plan_semanal_id'
                            ])
            ->innerJoin('planificacion_semanal', 'planificacion_semanal.id = planificacion_semanal_recursos.plan_semanal_id')
            ->innerJoin('scholaris_clase', 'scholaris_clase.id = planificacion_semanal.clase_id')
            ->innerJoin('op_faculty', 'op_faculty.id = scholaris_clase.idprofesor')
            ->innerJoin('res_users', 'res_users.partner_id = op_faculty.partner_id')
            ->where([
                'res_users.login' => $usuarioLogin,
                'planificacion_semanal_recursos.plan_semanal_id' => $planificacionSemanalId
            ])
            ;


            
        ;

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
            'planificacion_semanal_recursos.id' => $this->id,
            'planificacion_semanal_recursos.plan_semanal_id' => $this->plan_semanal_id,
            'planificacion_semanal_recursos.estado' => $this->estado,
        ]);

        $query->andFilterWhere(['ilike', 'planificacion_semanal_recursos.tema', $this->tema])
            ->andFilterWhere(['ilike', 'planificacion_semanal_recursos.tipo_recurso', $this->tipo_recurso])
            ->andFilterWhere(['ilike', 'planificacion_semanal_recursos.url_recurso', $this->url_recurso]);

        return $dataProvider;
    }
}
