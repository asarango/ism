<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_semanal".
 *
 * @property int $id
 * @property int $semana_id
 * @property int $clase_id
 * @property string $fecha
 * @property int $hora_id
 * @property int $orden_hora_semana
 * @property string $tema
 * @property string $actividades
 * @property string $diferenciacion_nee
 * @property string $recursos
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property ScholarisBloqueSemanas $semana
 * @property ScholarisClase $clase
 * @property ScholarisHorariov2Hora $hora
 */
class PlanificacionSemanal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_semanal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semana_id', 'clase_id', 'fecha', 'hora_id', 'orden_hora_semana', 'tema', 'actividades', 'diferenciacion_nee', 'recursos', 'created', 'created_at', 'updated', 'updated_at'], 'required'],
            [['semana_id', 'clase_id', 'hora_id', 'orden_hora_semana'], 'default', 'value' => null],
            [['semana_id', 'clase_id', 'hora_id', 'orden_hora_semana'], 'integer'],
            [['fecha', 'created_at', 'updated_at'], 'safe'],
            [['actividades', 'diferenciacion_nee', 'recursos'], 'string'],
            [['tema'], 'string', 'max' => 255],
            [['created', 'updated'], 'string', 'max' => 200],
            [['semana_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueSemanas::className(), 'targetAttribute' => ['semana_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
            [['hora_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Hora::className(), 'targetAttribute' => ['hora_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'semana_id' => 'Semana ID',
            'clase_id' => 'Clase ID',
            'fecha' => 'Fecha',
            'hora_id' => 'Hora ID',
            'orden_hora_semana' => 'Orden Hora Semana',
            'tema' => 'Tema',
            'actividades' => 'Actividades',
            'diferenciacion_nee' => 'Diferenciacion Nee',
            'recursos' => 'Recursos',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemana()
    {
        return $this->hasOne(ScholarisBloqueSemanas::className(), ['id' => 'semana_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHora()
    {
        return $this->hasOne(ScholarisHorariov2Hora::className(), ['id' => 'hora_id']);
    }
}
