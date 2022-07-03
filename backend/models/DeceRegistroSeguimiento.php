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
 * @property string $submotivo
 * @property string $submotivo2
 * @property string $persona_solicitante
 * @property string $atendido_por
 * @property string $atencion_para
 * @property string $responsable_seguimiento
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
            [['id_clase', 'id_estudiante'], 'default', 'value' => null],
            [['id_clase', 'id_estudiante'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['estado'], 'string', 'max' => 50],
            [['motivo', 'submotivo', 'submotivo2', 'persona_solicitante', 'atendido_por', 'atencion_para', 'responsable_seguimiento'], 'string', 'max' => 100],
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
            'submotivo' => 'Submotivo',
            'submotivo2' => 'Submotivo2',
            'persona_solicitante' => 'Persona Solicitante',
            'atendido_por' => 'Atendido Por',
            'atencion_para' => 'Atencion Para',
            'responsable_seguimiento' => 'Responsable Seguimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceRegistroAgendamientoAtencions()
    {
        return $this->hasMany(DeceRegistroAgendamientoAtencion::className(), ['id_reg_seguimiento' => 'id']);
    }
}
