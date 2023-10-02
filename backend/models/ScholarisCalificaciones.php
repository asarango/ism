<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificaciones".
 *
 * @property int $id
 * @property int $idalumno
 * @property int $idactividad
 * @property int $idtipoactividad
 * @property int $idperiodo
 * @property string $calificacion
 * @property string $observacion
 * @property int $criterio_id
 * @property int $estado_proceso
 * @property int $grupo_numero
 * @property int $estado
 * @property bool $aviso_padre_menos_70
 */
class ScholarisCalificaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idalumno', 'idactividad', 'idtipoactividad', 'idperiodo', 'criterio_id', 'estado_proceso', 'grupo_numero', 'estado'], 'default', 'value' => null],
            [['idalumno', 'idactividad', 'idtipoactividad', 'idperiodo', 'criterio_id', 'estado_proceso', 'grupo_numero', 'estado'], 'integer'],
            [['calificacion'], 'number'],
            [['aviso_padre_menos_70'], 'boolean'],
            [['observacion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idalumno' => 'Idalumno',
            'idactividad' => 'Idactividad',
            'idtipoactividad' => 'Idtipoactividad',
            'idperiodo' => 'Idperiodo',
            'calificacion' => 'Calificacion',
            'observacion' => 'Observacion',
            'criterio_id' => 'Criterio ID',
            'estado_proceso' => 'Estado Proceso',
            'grupo_numero' => 'Grupo Numero',
            'estado' => 'Estado',
            'aviso_padre_menos_70' => 'Aviso Padre Menos 70',
        ];
    }
}
