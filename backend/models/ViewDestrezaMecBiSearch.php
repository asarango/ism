<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ViewDestrezaMecBi;

/**
 * UsuarioSearch represents the model behind the search form of `backend\models\Usuario`.
 */
class ViewDestrezaMecBiSearch extends ViewDestrezaMecBi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso', 'criterio_eval_descripcion', 'destreza', 'materia', 
                'criterio_eval_codigo', 'destreza_codigo'], 'safe'],
            [['detalle_destreza_id', 'op_course_template_id', 'destreza_id', 
                'detalle_id', 'pep_planificacion_unidad_id'], 'integer'],
            // [['activo'], 'boolean'],
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
    public function search($params, $opCourseTemplateId)
    {
        $query = ViewDestrezaMecBi::find()
        ->where(['op_course_template_id' => $opCourseTemplateId])
        ->orderBy(['fecha_presentacion' => SORT_DESC]);

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
             'detalle_destreza_id' => $this->detalle_destreza_id,
             'op_course_template_id' => $this->op_course_template_id,
             'destreza_id' => $this->destreza_id,
             'detalle_id' => $this->detalle_id,
             'pep_planificacion_unidad_id' => $this->pep_planificacion_unidad_id,
        ]);

        $query->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'criterio_eval_descripcion', $this->criterio_eval_descripcion])
            ->andFilterWhere(['ilike', 'materia', $this->materia])
            ->andFilterWhere(['ilike', 'destreza', $this->destreza])
            ->andFilterWhere(['ilike', 'criterio_eval_codigo', $this->criterio_eval_codigo])
            ->andFilterWhere(['ilike', 'destreza_codigo', $this->destreza_codigo]);

        return $dataProvider;
    }
}