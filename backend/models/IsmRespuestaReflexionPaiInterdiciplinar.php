<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_respuesta_reflexion_pai_interdiciplinar".
 *
 * @property int $id
 * @property int $id_respuesta_plan_inter_pai
 * @property int $id_planificacion_opciones
 * @property string $respuesta
 *
 * @property IsmRespuestaPlanInterdiciplinar $respuestaPlanInterPai
 * @property PlanificacionOpciones $planificacionOpciones
 */
class IsmRespuestaReflexionPaiInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_respuesta_reflexion_pai_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_respuesta_plan_inter_pai', 'id_planificacion_opciones', 'respuesta'], 'required'],
            [['id_respuesta_plan_inter_pai', 'id_planificacion_opciones'], 'default', 'value' => null],
            [['id_respuesta_plan_inter_pai', 'id_planificacion_opciones'], 'integer'],
            [['respuesta'], 'string'],
            [['id_respuesta_plan_inter_pai'], 'exist', 'skipOnError' => true, 'targetClass' => IsmRespuestaPlanInterdiciplinar::className(), 'targetAttribute' => ['id_respuesta_plan_inter_pai' => 'id']],
            [['id_planificacion_opciones'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionOpciones::className(), 'targetAttribute' => ['id_planificacion_opciones' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_respuesta_plan_inter_pai' => 'Id Respuesta Plan Inter Pai',
            'id_planificacion_opciones' => 'Id Planificacion Opciones',
            'respuesta' => 'Respuesta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaPlanInterPai()
    {
        return $this->hasOne(IsmRespuestaPlanInterdiciplinar::className(), ['id' => 'id_respuesta_plan_inter_pai']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionOpciones()
    {
        return $this->hasOne(PlanificacionOpciones::className(), ['id' => 'id_planificacion_opciones']);
    }
}
