<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_kids_tareas".
 *
 * @property string $curso
 * @property string $paralelo
 * @property string $materia
 * @property string $fecha_presentacion
 * @property string $titulo
 */
class ViewKidsTareas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_kids_tareas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paralelo'], 'string'],
            [['tarea_id'], 'integer'],
            [['fecha_presentacion'], 'safe'],
            [['curso'], 'string', 'max' => 32],
            [['materia', 'titulo'], 'string', 'max' => 100],
            [['usuario'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'materia' => 'Materia',
            'fecha_presentacion' => 'Fecha Presentacion',
            'titulo' => 'Titulo',
            'usuario' => 'Usuario',
            'tarea_id' => 'Tarea ID'
        ];
    }
}
