<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_insumos".
 *
 * @property int $clase_id
 * @property string $curso
 * @property string $paralelo
 * @property string $nombre
 * @property string $nombre_nacional
 * @property int $actividad_id
 * @property string $inicio
 * @property string $title
 * @property string $login
 * @property int $periodo_id
 * @property int $total_calificados
 * @property int $total_estudiantes
 */
class ViewInsumos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_insumos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'actividad_id', 'periodo_id', 'total_calificados', 'total_estudiantes'], 'default', 'value' => null],
            [['clase_id', 'actividad_id', 'periodo_id', 'total_calificados', 'total_estudiantes'], 'integer'],
            [['paralelo'], 'string'],
            [['inicio'], 'safe'],
            [['curso'], 'string', 'max' => 32],
            [['nombre'], 'string', 'max' => 100],
            [['nombre_nacional'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 255],
            [['login'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clase_id' => 'Clase ID',
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'nombre' => 'Asignatura',
            'nombre_nacional' => 'Tipo de actividad',
            'actividad_id' => 'Tipo actividad',
            'inicio' => 'Fec. entrega',
            'title' => 'Título',
            'login' => 'Usuario',
            'periodo_id' => 'Periodo',
            'total_calificados' => 'Total Calificados',
            'total_estudiantes' => 'Total Estudiantes',
        ];
    }
}
