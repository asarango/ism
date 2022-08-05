<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pep_plan_semanal".
 *
 * @property int $id
 * @property int $pep_planificacion_id
 * @property string $experiencias_aprendizaje
 * @property string $evaluacion_continua
 * @property bool $es_aprobado
 * @property string $fecha_aprobacion
 * @property string $quien_aprueba
 * @property string $retroalimentacion
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property PepPlanificacionXUnidad $pepPlanificacion
 */
class PepPlanSemanal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pep_plan_semanal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pep_planificacion_id', 'experiencias_aprendizaje', 'evaluacion_continua', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['pep_planificacion_id'], 'default', 'value' => null],
            [['pep_planificacion_id'], 'integer'],
            [['experiencias_aprendizaje', 'evaluacion_continua', 'retroalimentacion'], 'string'],
            [['es_aprobado'], 'boolean'],
            [['fecha_aprobacion', 'created_at', 'updated_at'], 'safe'],
            [['quien_aprueba', 'created', 'updated'], 'string', 'max' => 200],
            [['pep_planificacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PepPlanificacionXUnidad::className(), 'targetAttribute' => ['pep_planificacion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pep_planificacion_id' => 'Pep Planificacion ID',
            'experiencias_aprendizaje' => 'Experiencias Aprendizaje',
            'evaluacion_continua' => 'Evaluacion Continua',
            'es_aprobado' => 'Es Aprobado',
            'fecha_aprobacion' => 'Fecha Aprobacion',
            'quien_aprueba' => 'Quien Aprueba',
            'retroalimentacion' => 'Retroalimentacion',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPepPlanificacion()
    {
        return $this->hasOne(PepPlanificacionXUnidad::className(), ['id' => 'pep_planificacion_id']);
    }
}
