<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pep_unidad_detalle".
 *
 * @property int $id
 * @property int $pep_planificacion_unidad_id
 * @property string $tipo
 * @property string $referencia
 * @property string $campo_de
 * @property string $contenido_texto
 * @property bool $contenido_opcion
 *
 * @property PepPlanificacionXUnidad $pepPlanificacionUnidad
 */
class PepUnidadDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pep_unidad_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pep_planificacion_unidad_id', 'tipo'], 'required'],
            [['pep_planificacion_unidad_id'], 'default', 'value' => null],
            [['pep_planificacion_unidad_id'], 'integer'],
            [['referencia', 'contenido_texto'], 'string'],
            [['contenido_opcion'], 'boolean'],
            [['tipo', 'campo_de'], 'string', 'max' => 30],
            [['pep_planificacion_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PepPlanificacionXUnidad::className(), 'targetAttribute' => ['pep_planificacion_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pep_planificacion_unidad_id' => 'Pep Planificacion Unidad ID',
            'tipo' => 'Tipo',
            'referencia' => 'Referencia',
            'campo_de' => 'Campo De',
            'contenido_texto' => 'Contenido Texto',
            'contenido_opcion' => 'Contenido Opcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPepPlanificacionUnidad()
    {
        return $this->hasOne(PepPlanificacionXUnidad::className(), ['id' => 'pep_planificacion_unidad_id']);
    }
}
