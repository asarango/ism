<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_contenido_pai_planificacion".
 *
 * @property int $id
 * @property int $planificacion_bloque_unidad_id
 * @property int $id_contenido_pai
 * @property bool $mostrar
 * @property string $tipo
 * @property string $contenido
 * @property string $actividad
 * @property string $objetivo
 * @property string $relacion_ods
 *
 * @property PlanificacionBloquesUnidad $planificacionBloqueUnidad
 */
class IsmContenidoPaiPlanificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_contenido_pai_planificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planificacion_bloque_unidad_id', 'id_contenido_pai', 'mostrar'], 'required'],
            [['planificacion_bloque_unidad_id', 'id_contenido_pai'], 'default', 'value' => null],
            [['planificacion_bloque_unidad_id', 'id_contenido_pai'], 'integer'],
            [['mostrar'], 'boolean'],
            [['actividad', 'objetivo', 'relacion_ods'], 'string'],
            [['tipo'], 'string', 'max' => 50],
            [['contenido'], 'string', 'max' => 100],
            [['planificacion_bloque_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['planificacion_bloque_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planificacion_bloque_unidad_id' => 'Planificacion Bloque Unidad ID',
            'id_contenido_pai' => 'Id Contenido Pai',
            'mostrar' => 'Mostrar',
            'tipo' => 'Tipo',
            'contenido' => 'Contenido',
            'actividad' => 'Actividad',
            'objetivo' => 'Objetivo',
            'relacion_ods' => 'Relacion Ods',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloqueUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'planificacion_bloque_unidad_id']);
    }
}
