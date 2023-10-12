<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_lista_visita_aulica".
 *
 * @property int $id
 * @property int $clase_id
 * @property string $curso
 * @property string $paralelo
 * @property string $docente
 * @property string $materia
 * @property string $usuario_dece
 * @property string $periodo_codigo
 * @property int $periodo_id
 * @property int $total
 */
class ViewListaVisitaAulica extends \yii\db\ActiveRecord
{

    public $id; // Columna virtual

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_lista_visita_aulica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'periodo_id', 'total'], 'default', 'value' => null],
            [['id', 'clase_id', 'periodo_id', 'total'], 'integer'],
            [['paralelo', 'docente'], 'string'],
            [['curso'], 'string', 'max' => 32],
            [['materia'], 'string', 'max' => 100],
            [['usuario_dece'], 'string', 'max' => 200],
            [['periodo_codigo'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'docente' => 'Docente',
            'materia' => 'Materia',
            'usuario_dece' => 'Usuario Dece',
            'periodo_codigo' => 'Periodo Codigo',
            'periodo_id' => 'Periodo ID',
            'total' => 'Total',
        ];
    }
}
