<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lms".
 *
 * @property int $id
 * @property int $ism_area_materia_id
 * @property int $tipo_bloque_comparte_valor
 * @property int $semana_numero
 * @property int $hora_numero
 * @property string $tipo_recurso
 * @property string $titulo
 * @property string $indicaciones
 * @property bool $publicar
 * @property bool $estado_activo
 * @property bool $es_aprobado
 * @property string $fecha_aprobacion
 * @property string $userio_aprobo
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 * @property string $descripcion_actividades
 * @property string $tarea
 * @property string $recursos
 * @property string $conceptos
 * @property string $dip_inicio
 * @property string $dip_desarrollo
 * @property string $dip_cierre
 * @property bool $posicion
 *
 * @property IsmAreaMateria $ismAreaMateria
 * @property LmsActividad[] $lmsActividads
 * @property LmsDocente[] $lmsDocentes
 */
class Lms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ism_area_materia_id', 'tipo_bloque_comparte_valor', 'semana_numero', 'hora_numero', 'tipo_recurso', 'titulo', 'created', 'created_at', 'updated', 'updated_at'], 'required'],
            [['ism_area_materia_id', 'tipo_bloque_comparte_valor', 'semana_numero', 'hora_numero'], 'default', 'value' => null],
            [['ism_area_materia_id', 'tipo_bloque_comparte_valor', 'semana_numero', 'hora_numero'], 'integer'],
            [['indicaciones', 'descripcion_actividades', 'tarea', 'recursos', 'conceptos', 'dip_inicio', 'dip_desarrollo', 'dip_cierre'], 'string'],
            [['publicar', 'estado_activo', 'es_aprobado', 'posicion'], 'boolean'],
            [['fecha_aprobacion', 'created_at', 'updated_at'], 'safe'],
            [['tipo_recurso'], 'string', 'max' => 40],
            [['titulo'], 'string', 'max' => 150],
            [['userio_aprobo', 'created', 'updated'], 'string', 'max' => 200],
            [['ism_area_materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmAreaMateria::className(), 'targetAttribute' => ['ism_area_materia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ism_area_materia_id' => 'Ism Area Materia ID',
            'tipo_bloque_comparte_valor' => 'Tipo Bloque Comparte Valor',
            'semana_numero' => 'Semana Numero',
            'hora_numero' => 'Hora Numero',
            'tipo_recurso' => 'Tipo Recurso',
            'titulo' => 'Titulo',
            'indicaciones' => 'Indicaciones',
            'publicar' => 'Publicar',
            'estado_activo' => 'Estado Activo',
            'es_aprobado' => 'Es Aprobado',
            'fecha_aprobacion' => 'Fecha Aprobacion',
            'userio_aprobo' => 'Userio Aprobo',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
            'descripcion_actividades' => 'Descripcion Actividades',
            'tarea' => 'Tarea',
            'recursos' => 'Recursos',
            'conceptos' => 'Conceptos',
            'dip_inicio' => 'Dip Inicio',
            'dip_desarrollo' => 'Dip Desarrollo',
            'dip_cierre' => 'Dip Cierre',
            'posicion' => 'Posicion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMateria()
    {
        return $this->hasOne(IsmAreaMateria::className(), ['id' => 'ism_area_materia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsActividads()
    {
        return $this->hasMany(LmsActividad::className(), ['lms_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsDocentes()
    {
        return $this->hasMany(LmsDocente::className(), ['lms_id' => 'id']);
    }
}
