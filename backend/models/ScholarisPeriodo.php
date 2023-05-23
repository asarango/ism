<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_periodo".
 *
 * @property int $id
 * @property int $usuario_creo
 * @property string $creado
 * @property string $nombre
 * @property int $usuario_actualizo
 * @property string $actualizado
 * @property string $codigo
 * @property bool $estado
 * @property string $tipo_calificacion
 * @property string $version_calculo_notas
 *
 * @property DeceCasos[] $deceCasos
 * @property InspFechaPeriodo[] $inspFechaPeriodos
 * @property IsmGrupoPlanInterdiciplinar[] $ismGrupoPlanInterdiciplinars
 * @property IsmPeriodoMalla[] $ismPeriodoMallas
 * @property KidsPcaBitacora[] $kidsPcaBitacoras
 * @property LibArea[] $libAreas
 * @property LibBloquesGrupoClase[] $libBloquesGrupoClases
 * @property LibPromediosInsumos[] $libPromediosInsumos
 * @property MapaEnfoquesPai[] $mapaEnfoquesPais
 * @property MapaEnfoquesPaiAprobacion[] $mapaEnfoquesPaiAprobacions
 * @property MessageGroup[] $messageGroups
 * @property Nee[] $nees
 * @property PepPlanificacionXUnidad[] $pepPlanificacionXUnidads
 * @property PlanificacionDesagregacionCabecera[] $planificacionDesagregacionCabeceras
 * @property ScholarisBloqueActividad[] $scholarisBloqueActividads
 * @property ScholarisFaltas[] $scholarisFaltas
 * @property ScholarisFechasCierreAnio[] $scholarisFechasCierreAnios
 * @property ScholarisHorariov2Cabecera[] $scholarisHorariov2Cabeceras
 * @property ScholarisMalla[] $scholarisMallas
 * @property ScholarisMecV2Malla[] $scholarisMecV2Mallas
 * @property ScholarisOpPeriodPeriodoScholaris[] $scholarisOpPeriodPeriodoScholaris
 * @property OpPeriod[] $ops
 * @property ScholarisPromediosAnuales[] $scholarisPromediosAnuales
 * @property OpStudentInscription[] $alumnoInscriptions
 * @property ScholarisQuimestre[] $scholarisQuimestres
 * @property ScholarisQuimestreTipoCalificacion[] $scholarisQuimestreTipoCalificacions
 * @property ScholarisTipoCalificacionPeriodo[] $scholarisTipoCalificacionPeriodos
 */
class ScholarisPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_creo', 'usuario_actualizo'], 'default', 'value' => null],
            [['usuario_creo', 'usuario_actualizo'], 'integer'],
            [['creado', 'actualizado', 'codigo', 'estado'], 'required'],
            [['creado', 'actualizado'], 'safe'],
            [['estado'], 'boolean'],
            [['nombre'], 'string', 'max' => 50],
            [['codigo'], 'string', 'max' => 20],
            [['tipo_calificacion'], 'string', 'max' => 30],
            [['version_calculo_notas'], 'string', 'max' => 40],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_creo' => 'Usuario Creo',
            'creado' => 'Creado',
            'nombre' => 'Nombre',
            'usuario_actualizo' => 'Usuario Actualizo',
            'actualizado' => 'Actualizado',
            'codigo' => 'Codigo',
            'estado' => 'Estado',
            'tipo_calificacion' => 'Tipo Calificacion',
            'version_calculo_notas' => 'Version Calculo Notas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceCasos()
    {
        return $this->hasMany(DeceCasos::className(), ['id_periodo' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInspFechaPeriodos()
    {
        return $this->hasMany(InspFechaPeriodo::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmGrupoPlanInterdiciplinars()
    {
        return $this->hasMany(IsmGrupoPlanInterdiciplinar::className(), ['id_periodo' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmPeriodoMallas()
    {
        return $this->hasMany(IsmPeriodoMalla::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidsPcaBitacoras()
    {
        return $this->hasMany(KidsPcaBitacora::className(), ['pca_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibAreas()
    {
        return $this->hasMany(LibArea::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibBloquesGrupoClases()
    {
        return $this->hasMany(LibBloquesGrupoClase::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibPromediosInsumos()
    {
        return $this->hasMany(LibPromediosInsumos::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapaEnfoquesPais()
    {
        return $this->hasMany(MapaEnfoquesPai::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapaEnfoquesPaiAprobacions()
    {
        return $this->hasMany(MapaEnfoquesPaiAprobacion::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageGroups()
    {
        return $this->hasMany(MessageGroup::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNees()
    {
        return $this->hasMany(Nee::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPepPlanificacionXUnidads()
    {
        return $this->hasMany(PepPlanificacionXUnidad::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionDesagregacionCabeceras()
    {
        return $this->hasMany(PlanificacionDesagregacionCabecera::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisBloqueActividads()
    {
        return $this->hasMany(ScholarisBloqueActividad::className(), ['scholaris_periodo_codigo' => 'codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisFaltas()
    {
        return $this->hasMany(ScholarisFaltas::className(), ['scholaris_perido_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisFechasCierreAnios()
    {
        return $this->hasMany(ScholarisFechasCierreAnio::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisHorariov2Cabeceras()
    {
        return $this->hasMany(ScholarisHorariov2Cabecera::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMallas()
    {
        return $this->hasMany(ScholarisMalla::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2Mallas()
    {
        return $this->hasMany(ScholarisMecV2Malla::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisOpPeriodPeriodoScholaris()
    {
        return $this->hasMany(ScholarisOpPeriodPeriodoScholaris::className(), ['scholaris_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOps()
    {
        return $this->hasMany(OpPeriod::className(), ['id' => 'op_id'])->viaTable('scholaris_op_period_periodo_scholaris', ['scholaris_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPromediosAnuales()
    {
        return $this->hasMany(ScholarisPromediosAnuales::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscriptions()
    {
        return $this->hasMany(OpStudentInscription::className(), ['id' => 'alumno_inscription_id'])->viaTable('scholaris_promedios_anuales', ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisQuimestres()
    {
        return $this->hasMany(ScholarisQuimestre::className(), ['scholaris_periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisQuimestreTipoCalificacions()
    {
        return $this->hasMany(ScholarisQuimestreTipoCalificacion::className(), ['periodo_scholaris_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisTipoCalificacionPeriodos()
    {
        return $this->hasMany(ScholarisTipoCalificacionPeriodo::className(), ['scholaris_periodo_id' => 'id']);
    }
}
