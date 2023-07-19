<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "toc_plan_unidad_detalle".
 *
 * @property int $id
 * @property int $toc_plan_unidad_id
 * @property string $evaluacion_pd
 * @property string $descripcion_unidad
 * @property string $preguntas_conocimiento
 * @property string $conocimientos_esenciales
 * @property string $actividades_principales
 * @property string $enfoques_aprendizaje
 * @property string $funciono_bien
 * @property string $no_funciono_bien
 * @property string $observaciones
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 * @property string $diferenciacion
 *
 * @property TocPlanUnidad $tocPlanUnidad
 */
class TocPlanUnidadDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toc_plan_unidad_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toc_plan_unidad_id', 'created', 'created_at', 'updated', 'updated_at'], 'required'],
            [['toc_plan_unidad_id'], 'default', 'value' => null],
            [['toc_plan_unidad_id'], 'integer'],
            [['evaluacion_pd', 'descripcion_unidad', 'preguntas_conocimiento', 'conocimientos_esenciales', 'actividades_principales', 'enfoques_aprendizaje', 'funciono_bien', 'no_funciono_bien', 'observaciones', 'diferenciacion'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['toc_plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => TocPlanUnidad::className(), 'targetAttribute' => ['toc_plan_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'toc_plan_unidad_id' => 'Toc Plan Unidad ID',
            'evaluacion_pd' => 'Evaluacion Pd',
            'descripcion_unidad' => 'Descripcion Unidad',
            'preguntas_conocimiento' => 'Preguntas Conocimiento',
            'conocimientos_esenciales' => 'Conocimientos Esenciales',
            'actividades_principales' => 'Actividades Principales',
            'enfoques_aprendizaje' => 'Enfoques Aprendizaje',
            'funciono_bien' => 'Funciono Bien',
            'no_funciono_bien' => 'No Funciono Bien',
            'observaciones' => 'Observaciones',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
            'diferenciacion' => 'Diferenciacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTocPlanUnidad()
    {
        return $this->hasOne(TocPlanUnidad::className(), ['id' => 'toc_plan_unidad_id']);
    }
}
