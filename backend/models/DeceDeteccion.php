<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_deteccion".
 *
 * @property int $id
 * @property int $numero_deteccion
 * @property int $id_estudiante
 * @property int $id_caso
 * @property int $numero_caso
 * @property string $nombre_estudiante
 * @property string $anio
 * @property string $paralelo
 * @property string $nombre_quien_reporta
 * @property string $cargo
 * @property string $cedula
 * @property string $fecha_reporte
 * @property string $descripcion_del_hecho
 * @property string $hora_aproximada
 * @property string $acciones_realizadas
 * @property string $lista_evidencias
 * @property string $path_archivos
 *
 * @property DeceCasos $caso
 */
class DeceDeteccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_deteccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_deteccion', 'id_caso', 'numero_caso', 'nombre_estudiante', 'anio', 'paralelo', 'nombre_quien_reporta', 'cargo', 'cedula', 'fecha_reporte', 'descripcion_del_hecho', 'hora_aproximada', 'acciones_realizadas', 'lista_evidencias', 'path_archivos'], 'required'],
            [['numero_deteccion', 'id_estudiante', 'id_caso', 'numero_caso'], 'default', 'value' => null],
            [['numero_deteccion', 'id_estudiante', 'id_caso', 'numero_caso'], 'integer'],
            [['fecha_reporte'], 'safe'],
            [['nombre_estudiante', 'nombre_quien_reporta', 'path_archivos'], 'string', 'max' => 100],
            [['anio', 'paralelo', 'cargo'], 'string', 'max' => 50],
            [['cedula', 'hora_aproximada'], 'string', 'max' => 20],
            [['descripcion_del_hecho', 'acciones_realizadas', 'lista_evidencias'], 'string', 'max' => 2000],
            [['id_caso'], 'exist', 'skipOnError' => true, 'targetClass' => DeceCasos::className(), 'targetAttribute' => ['id_caso' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_deteccion' => 'Numero Deteccion',
            'id_estudiante' => 'Id Estudiante',
            'id_caso' => 'Id Caso',
            'numero_caso' => 'Numero Caso',
            'nombre_estudiante' => 'Nombre Estudiante',
            'anio' => 'Año',
            'paralelo' => 'Paralelo',
            'nombre_quien_reporta' => 'Nombre',
            'cargo' => 'Cargo',
            'cedula' => 'Cédula',
            'fecha_reporte' => 'Fecha',
            'descripcion_del_hecho' => 'Descripción Del Hecho',
            'hora_aproximada' => 'Hora Aproximada',
            'acciones_realizadas' => 'Acciones Realizadas',
            'lista_evidencias' => 'Lista Evidencias',
            'path_archivos' => 'Path Archivos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaso()
    {
        return $this->hasOne(DeceCasos::className(), ['id' => 'id_caso']);
    }
   
}
