<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_registro_agendamiento_atencion".
 *
 * @property int $id
 * @property int $id_reg_seguimiento
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $estado
 * @property string $pronunciamiento
 * @property string $acuerdo_y_compromiso
 * @property string $evidencia
 * @property string $path_archivo
 *
 * @property DeceAsistente[] $deceAsistentes
 * @property DeceRegistroSeguimiento $regSeguimiento
 */
class DeceRegistroAgendamientoAtencion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_registro_agendamiento_atencion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reg_seguimiento'], 'required'],
            [['id_reg_seguimiento'], 'default', 'value' => null],
            [['id_reg_seguimiento'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['estado'], 'string', 'max' => 50],
            [['pronunciamiento', 'acuerdo_y_compromiso', 'evidencia'], 'string', 'max' => 500],
            [['path_archivo'], 'string', 'max' => 250],
            [['id_reg_seguimiento'], 'exist', 'skipOnError' => true, 'targetClass' => DeceRegistroSeguimiento::className(), 'targetAttribute' => ['id_reg_seguimiento' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reg_seguimiento' => 'Id Reg Seguimiento',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'estado' => 'Estado',
            'pronunciamiento' => 'Pronunciamiento',
            'acuerdo_y_compromiso' => 'Acuerdo Y Compromiso',
            'evidencia' => 'Evidencia',
            'path_archivo' => 'Path Archivo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceAsistentes()
    {
        return $this->hasMany(DeceAsistente::className(), ['id_dece_reg_agend_atencion' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegSeguimiento()
    {
        return $this->hasOne(DeceRegistroSeguimiento::className(), ['id' => 'id_reg_seguimiento']);
    }
}
