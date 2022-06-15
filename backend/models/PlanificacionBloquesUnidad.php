<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_bloques_unidad".
 *
 * @property int $id
 * @property int $curriculo_bloque_id
 * @property int $plan_cabecera_id
 * @property string $unit_title
 * @property string $settings_status
 * @property string $enunciado_indagacion
 * @property string $contenidos
 * @property bool $is_open
 * @property bool $pud_status
 * @property int $avance_porcentaje
 *
 * @property CurriculoMecBloque $curriculoBloque
 * @property PlanificacionDesagregacionCabecera $planCabecera
 * @property PlanificacionBloquesUnidadSubtitulo[] $planificacionBloquesUnidadSubtitulos
 * @property PlanificacionDesagregacionCriteriosEvaluacion[] $planificacionDesagregacionCriteriosEvaluacions
 * @property PlanificacionVerticalDiploma[] $planificacionVerticalDiplomas
 * @property PlanificacionVerticalPaiDescriptores[] $planificacionVerticalPaiDescriptores
 * @property PlanificacionVerticalPaiOpciones[] $planificacionVerticalPaiOpciones
 * @property PudPai[] $pudPais
 * @property PudPaiServicioAccion[] $pudPaiServicioAccions
 * @property PudPep[] $pudPeps
 */
class PlanificacionBloquesUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_bloques_unidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curriculo_bloque_id', 'plan_cabecera_id', 'unit_title', 'settings_status'], 'required'],
            [['curriculo_bloque_id', 'plan_cabecera_id', 'avance_porcentaje'], 'default', 'value' => null],
            [['curriculo_bloque_id', 'plan_cabecera_id', 'avance_porcentaje'], 'integer'],
            [['enunciado_indagacion', 'contenidos'], 'string'],
            [['is_open', 'pud_status'], 'boolean'],
            [['unit_title'], 'string', 'max' => 150],
            [['settings_status'], 'string', 'max' => 30],
            [['curriculo_bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecBloque::className(), 'targetAttribute' => ['curriculo_bloque_id' => 'id']],
            [['plan_cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCabecera::className(), 'targetAttribute' => ['plan_cabecera_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'curriculo_bloque_id' => 'Curriculo Bloque ID',
            'plan_cabecera_id' => 'Plan Cabecera ID',
            'unit_title' => 'Unit Title',
            'settings_status' => 'Settings Status',
            'enunciado_indagacion' => 'Enunciado Indagacion',
            'contenidos' => 'Contenidos',
            'is_open' => 'Is Open',
            'pud_status' => 'Pud Status',
            'avance_porcentaje' => 'Avance Porcentaje',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculoBloque()
    {
        return $this->hasOne(CurriculoMecBloque::className(), ['id' => 'curriculo_bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanCabecera()
    {
        return $this->hasOne(PlanificacionDesagregacionCabecera::className(), ['id' => 'plan_cabecera_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloquesUnidadSubtitulos()
    {
        return $this->hasMany(PlanificacionBloquesUnidadSubtitulo::className(), ['plan_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionDesagregacionCriteriosEvaluacions()
    {
        return $this->hasMany(PlanificacionDesagregacionCriteriosEvaluacion::className(), ['bloque_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionVerticalDiplomas()
    {
        return $this->hasMany(PlanificacionVerticalDiploma::className(), ['planificacion_bloque_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionVerticalPaiDescriptores()
    {
        return $this->hasMany(PlanificacionVerticalPaiDescriptores::className(), ['plan_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionVerticalPaiOpciones()
    {
        return $this->hasMany(PlanificacionVerticalPaiOpciones::className(), ['plan_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPudPais()
    {
        return $this->hasMany(PudPai::className(), ['planificacion_bloque_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPudPaiServicioAccions()
    {
        return $this->hasMany(PudPaiServicioAccion::className(), ['planificacion_bloque_unidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPudPeps()
    {
        return $this->hasMany(PudPep::className(), ['planificacion_bloque_unidad_id' => 'id']);
    }
}
