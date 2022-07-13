<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ViewKidsTareas;

/**
 * UsuarioSearch represents the model behind the search form of `backend\models\Usuario`.
 */
class ViewKidsTareasSearch extends ViewKidsTareas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso', 'paralelo', 'materia', 'fecha_presentacion', 'titulo'], 'safe'],
            // [['rol_id', 'numero_incremento', 'instituto_defecto', 'periodo_id'], 'integer'],
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
    public function search($params, $usuario)
    {
        $query = ViewKidsTareas::find()
        ->where(['usuario' => $usuario])
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
            // 'rol_id' => $this->rol_id,
            // 'activo' => $this->activo,
            // 'numero_incremento' => $this->numero_incremento,
            // 'instituto_defecto' => $this->instituto_defecto,
            // 'periodo_id' => $this->periodo_id,
        ]);

        $query->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'materia', $this->materia])
            ->andFilterWhere(['ilike', 'fecha_presentacion', $this->fecha_presentacion])
            ->andFilterWhere(['ilike', 'usuario', $this->usuario])
            ->andFilterWhere(['ilike', 'titulo', $this->titulo]);

        return $dataProvider;
    }
}
