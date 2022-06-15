<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_aprobacion_bitacora".
 *
 * @property int $id
 * @property int $pud_id
 * @property string $notificacion
 * @property string $usuario_notifica
 * @property string $fecha_notifica
 * @property string $respuesta
 * @property string $usuario_responde
 * @property string $fecha_responde
 * @property string $estado_jefe_coordinador
 *
 * @property PlanificacionDesagregacionCabecera $pud
 */
class PudAprobacionBitacora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_aprobacion_bitacora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pud_id', 'estado_jefe_coordinador'], 'required'],
            [['pud_id'], 'default', 'value' => null],
            [['pud_id'], 'integer'],
            [['notificacion', 'respuesta'], 'string'],
            [['fecha_notifica', 'fecha_responde'], 'safe'],
            [['usuario_notifica', 'usuario_responde'], 'string', 'max' => 200],
            [['estado_jefe_coordinador'], 'string', 'max' => 50],
            [['pud_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCabecera::className(), 'targetAttribute' => ['pud_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pud_id' => 'Pud ID',
            'notificacion' => 'Notificacion',
            'usuario_notifica' => 'Usuario Notifica',
            'fecha_notifica' => 'Fecha Notifica',
            'respuesta' => 'Respuesta',
            'usuario_responde' => 'Usuario Responde',
            'fecha_responde' => 'Fecha Responde',
            'estado_jefe_coordinador' => 'Estado Jefe Coordinador',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPud()
    {
        return $this->hasOne(PlanificacionDesagregacionCabecera::className(), ['id' => 'pud_id']);
    }
}
