<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_registro_seguimiento".
 *
 * @property int $id
 * @property int $id_clase
 * @property int $id_estudiante
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $estado
 * @property string $motivo
 * @property string $persona_solicitante
 * @property string $atendido_por
 * @property string $atencion_para
 * @property string $responsable_seguimiento
 * @property string $pronunciamiento
 * @property string $acuerdo_y_compromiso
 * @property string $eviencia
 * @property string $path_archivo
 * @property int $id_caso
 * @property int $numero_seguimiento
 * @property string $departamento
 * @property string $nombre_quien_lidera
 * @property string $hora_inicio
 * @property string $hora_cierre
 *
 * @property DeceRegistroAgendamientoAtencion[] $deceRegistroAgendamientoAtencions
 * @property DeceCasos $caso
 * @property DeceCasos $caso0
 * @property DeceSeguimientoAcuerdos[] $deceSeguimientoAcuerdos
 * @property DeceSeguimientoFirmas[] $deceSeguimientoFirmas
 */
class DeceRegistroSeguimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_registro_seguimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_clase', 'id_estudiante', 'id_caso', 'numero_seguimiento'], 'default', 'value' => null],
            [['id_clase', 'id_estudiante', 'id_caso', 'numero_seguimiento'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['pronunciamiento', 'acuerdo_y_compromiso', 'eviencia'], 'string'],
            [['estado'], 'string', 'max' => 50],
            [['motivo', 'persona_solicitante', 'atendido_por', 'atencion_para', 'responsable_seguimiento'], 'string', 'max' => 100],
            [['path_archivo'], 'string', 'max' => 1000],
            [['departamento', 'nombre_quien_lidera'], 'string', 'max' => 200],
            [['hora_inicio', 'hora_cierre'], 'string', 'max' => 20],
            [['id_caso'], 'exist', 'skipOnError' => true, 'targetClass' => DeceCasos::className(), 'targetAttribute' => ['id_caso' => 'id']],
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
            'id_clase' => 'Id Clase',
            'id_estudiante' => 'Id Estudiante',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'estado' => 'Estado',
            'motivo' => 'Motivo',
            'persona_solicitante' => 'Persona Solicitante',
            'atendido_por' => 'Atendido Por',
            'atencion_para' => 'Atencion Para',
            'responsable_seguimiento' => 'Responsable Seguimiento',
            'pronunciamiento' => 'Detalle del Seguimiento',
            'acuerdo_y_compromiso' => 'Acuerdo Y Compromiso',
            'eviencia' => 'Eviencia',
            'path_archivo' => 'Path Archivo',
            'id_caso' => 'Id Caso',
            'numero_seguimiento' => 'Numero Seguimiento',
            'departamento' => 'Departamento',
            'nombre_quien_lidera' => 'Nombre Quien Lidera',
            'hora_inicio' => 'Hora Inicio',
            'hora_cierre' => 'Hora Cierre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceRegistroAgendamientoAtencions()
    {
        return $this->hasMany(DeceRegistroAgendamientoAtencion::className(), ['id_reg_seguimiento' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaso()
    {
        return $this->hasOne(DeceCasos::className(), ['id' => 'id_caso']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaso0()
    {
        return $this->hasOne(DeceCasos::className(), ['id' => 'id_caso']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceSeguimientoAcuerdos()
    {
        return $this->hasMany(DeceSeguimientoAcuerdos::className(), ['id_reg_seguimiento' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceSeguimientoFirmas()
    {
        return $this->hasMany(DeceSeguimientoFirmas::className(), ['id_reg_seguimiento' => 'id']);
    }
}
