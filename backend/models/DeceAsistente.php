<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_asistente".
 *
 * @property int $id
 * @property int $id_dece_reg_agend_atencion
 * @property string $tipo
 * @property string $nombre
 * @property string $cedula
 * @property string $parentesco
 * @property string $correo
 *
 * @property DeceRegistroAgendamientoAtencion $deceRegAgendAtencion
 */
class DeceAsistente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_asistente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dece_reg_agend_atencion'], 'required'],
            [['id_dece_reg_agend_atencion'], 'default', 'value' => null],
            [['id_dece_reg_agend_atencion'], 'integer'],
            [['tipo', 'correo'], 'string', 'max' => 50],
            [['nombre'], 'string', 'max' => 100],
            [['cedula'], 'string', 'max' => 13],
            [['parentesco'], 'string', 'max' => 20],
            [['id_dece_reg_agend_atencion'], 'exist', 'skipOnError' => true, 'targetClass' => DeceRegistroAgendamientoAtencion::className(), 'targetAttribute' => ['id_dece_reg_agend_atencion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dece_reg_agend_atencion' => 'Id Dece Reg Agend Atencion',
            'tipo' => 'Tipo',
            'nombre' => 'Nombre',
            'cedula' => 'Cedula',
            'parentesco' => 'Parentesco',
            'correo' => 'Correo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceRegAgendAtencion()
    {
        return $this->hasOne(DeceRegistroAgendamientoAtencion::className(), ['id' => 'id_dece_reg_agend_atencion']);
    }
}
