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
 *
 * @property DeceRegistroAgendamientoAtencion[] $deceRegistroAgendamientoAtencions
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
            [['fecha_inicio','pronunciamiento','acuerdo_y_compromiso','estado','motivo','atendido_por','id_caso'], 'required'],
            [['id_clase', 'id_estudiante'], 'default', 'value' => null],
            [['id_clase', 'id_estudiante','id_caso'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['pronunciamiento', 'acuerdo_y_compromiso', 'eviencia'], 'string'],
            [['estado'], 'string', 'max' => 50],
            [['motivo', 'persona_solicitante', 'atendido_por', 'atencion_para', 'responsable_seguimiento'], 'string', 'max' => 100],
            [['path_archivo'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_caso'=>'Id Caso',
            'id_clase' => 'Id Clase',
            'id_estudiante' => 'Id Estudiante',
            'fecha_inicio' => 'Fecha',
            'fecha_fin' => 'Fecha',
            'estado' => 'Estado',
            'motivo' => 'Motivo',
            'persona_solicitante' => 'Solicitante',
            'atendido_por' => 'Atendido Por',
            'atencion_para' => 'Atencion Para',
            'responsable_seguimiento' => 'Responsable Seguimiento',
            'pronunciamiento' => 'Pronunciamiento',
            'acuerdo_y_compromiso' => 'Acuerdo y Compromiso',
            'eviencia' => 'Evidencia',
            'path_archivo' => 'Path Archivo',
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
}
