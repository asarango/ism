<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_actividad_crear".
 *
 * @property int $id
 * @property int $plan_id
 * @property string $curso
 * @property string $paralelo
 * @property string $trimestre
 * @property string $nombre_semana
 * @property string $fecha
 * @property string $hora
 * @property string $materia
 * @property string $tema
 * @property string $login
 */
class ViewActividadCrear extends \yii\db\ActiveRecord
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
        return 'view_actividad_crear';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id'], 'default', 'value' => null],
            [['plan_id'], 'integer'],
            [['paralelo'], 'string'],
            [['fecha'], 'safe'],
            [['curso'], 'string', 'max' => 32],
            [['trimestre'], 'string', 'max' => 50],
            [['nombre_semana'], 'string', 'max' => 80],
            [['hora'], 'string', 'max' => 20],
            [['materia'], 'string', 'max' => 100],
            [['tema'], 'string', 'max' => 255],
            [['login'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => 'Plan ID',
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'trimestre' => 'Trimestre',
            'nombre_semana' => 'Nombre Semana',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'materia' => 'Materia',
            'tema' => 'Tema',
            'login' => 'Login',
        ];
    }
}
