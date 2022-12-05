<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_actividad".
 *
 * @property int $id
 * @property string $name Nombre
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property string $quimestre Quimestre
 * @property string $tipo Tipo
 * @property string $desde Desde
 * @property string $hasta Hasta
 * @property int $orden Orden
 * @property string $scholaris_periodo_codigo
 * @property string $tipo_bloque
 * @property int $dias_laborados
 * @property string $estado
 * @property string $abreviatura
 * @property string $tipo_uso
 * @property string $bloque_inicia
 * @property string $bloque_finaliza
 * @property int $instituto_id
 * @property string $codigo_tipo_calificacion
 * @property string $fecha_aprobacion_pud
 * @property string $fecha_aprobacion_pca
 *
 * @property LibArea[] $libAreas
 * @property LibBloquesGrupoClase[] $libBloquesGrupoClases
 * @property LibPromedio[] $libPromedios
 * @property LibPromediosInsumos[] $libPromediosInsumos
 * @property ScholarisArchivosPud[] $scholarisArchivosPuds
 * @property OpInstitute $instituto
 * @property ScholarisBloqueComoCalifica $codigoTipoCalificacion
 * @property ScholarisPeriodo $scholarisPeriodoCodigo
 * @property ScholarisBloqueSemanas[] $scholarisBloqueSemanas
 * @property ScholarisCalificaComportamiento[] $scholarisCalificaComportamientos
 * @property ScholarisCalificacionesParcial[] $scholarisCalificacionesParcials
 * @property ScholarisCalificacionesParcialCambios[] $scholarisCalificacionesParcialCambios
 * @property ScholarisFaltasYAtrasosParcial[] $scholarisFaltasYAtrasosParcials
 * @property ScholarisRefuerzo[] $scholarisRefuerzos
 * @property ScholarisResumenParciales[] $scholarisResumenParciales
 * @property ScholarisTomaAsistecia[] $scholarisTomaAsistecias
 */
class ScholarisBloqueActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['create_uid', 'write_uid', 'orden', 'dias_laborados', 'instituto_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'orden', 'dias_laborados', 'instituto_id'], 'integer'],
            [['create_date', 'write_date', 'desde', 'hasta', 'bloque_inicia', 'bloque_finaliza', 'fecha_aprobacion_pud', 'fecha_aprobacion_pca'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['quimestre', 'tipo', 'scholaris_periodo_codigo', 'tipo_bloque'], 'string', 'max' => 20],
            [['estado', 'tipo_uso', 'codigo_tipo_calificacion'], 'string', 'max' => 30],
            [['abreviatura'], 'string', 'max' => 5],
            [['instituto_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['instituto_id' => 'id']],
            [['codigo_tipo_calificacion'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueComoCalifica::className(), 'targetAttribute' => ['codigo_tipo_calificacion' => 'codigo']],
            [['scholaris_periodo_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_codigo' => 'codigo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'quimestre' => 'Quimestre',
            'tipo' => 'Tipo',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'orden' => 'Orden',
            'scholaris_periodo_codigo' => 'Scholaris Periodo Codigo',
            'tipo_bloque' => 'Tipo Bloque',
            'dias_laborados' => 'Dias Laborados',
            'estado' => 'Estado',
            'abreviatura' => 'Abreviatura',
            'tipo_uso' => 'Tipo Uso',
            'bloque_inicia' => 'Bloque Inicia',
            'bloque_finaliza' => 'Bloque Finaliza',
            'instituto_id' => 'Instituto ID',
            'codigo_tipo_calificacion' => 'Codigo Tipo Calificacion',
            'fecha_aprobacion_pud' => 'Fecha Aprobacion Pud',
            'fecha_aprobacion_pca' => 'Fecha Aprobacion Pca',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibAreas()
    {
        return $this->hasMany(LibArea::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibBloquesGrupoClases()
    {
        return $this->hasMany(LibBloquesGrupoClase::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibPromedios()
    {
        return $this->hasMany(LibPromedio::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibPromediosInsumos()
    {
        return $this->hasMany(LibPromediosInsumos::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisArchivosPuds()
    {
        return $this->hasMany(ScholarisArchivosPud::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituto()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'instituto_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoTipoCalificacion()
    {
        return $this->hasOne(ScholarisBloqueComoCalifica::className(), ['codigo' => 'codigo_tipo_calificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodoCodigo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['codigo' => 'scholaris_periodo_codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisBloqueSemanas()
    {
        return $this->hasMany(ScholarisBloqueSemanas::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisCalificaComportamientos()
    {
        return $this->hasMany(ScholarisCalificaComportamiento::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisCalificacionesParcials()
    {
        return $this->hasMany(ScholarisCalificacionesParcial::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisCalificacionesParcialCambios()
    {
        return $this->hasMany(ScholarisCalificacionesParcialCambios::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisFaltasYAtrasosParcials()
    {
        return $this->hasMany(ScholarisFaltasYAtrasosParcial::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisRefuerzos()
    {
        return $this->hasMany(ScholarisRefuerzo::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisResumenParciales()
    {
        return $this->hasMany(ScholarisResumenParciales::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisTomaAsistecias()
    {
        return $this->hasMany(ScholarisTomaAsistecia::className(), ['bloque_id' => 'id']);
    }


    public function getUso(){        
        return $this->hasOne(ScholarisBloqueComparte::className(), ['valor' => 'tipo_uso']);
    }
}
