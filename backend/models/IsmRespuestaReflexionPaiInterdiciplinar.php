<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_respuesta_reflexion_pai_interdiciplinar".
 *
 * @property int $id
 * @property int $id_respuesta_plan_inter_pai
 * @property int $id_preguntas_reflexion
 * @property string $respuesta
 *
 * @property IsmPreguntasReflexionPaiInterdisciplinar $preguntasReflexion
 * @property IsmRespuestaPlanInterdiciplinar $respuestaPlanInterPai
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
            [['id_respuesta_plan_inter_pai', 'id_preguntas_reflexion', 'respuesta'], 'required'],
            [['id_respuesta_plan_inter_pai', 'id_preguntas_reflexion'], 'default', 'value' => null],
            [['id_respuesta_plan_inter_pai', 'id_preguntas_reflexion'], 'integer'],
            [['respuesta'], 'string'],
            [['id_preguntas_reflexion'], 'exist', 'skipOnError' => true, 'targetClass' => IsmPreguntasReflexionPaiInterdisciplinar::className(), 'targetAttribute' => ['id_preguntas_reflexion' => 'id']],
            [['id_respuesta_plan_inter_pai'], 'exist', 'skipOnError' => true, 'targetClass' => IsmRespuestaPlanInterdiciplinar::className(), 'targetAttribute' => ['id_respuesta_plan_inter_pai' => 'id']],
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
            'id_preguntas_reflexion' => 'Id Preguntas Reflexion',
            'respuesta' => 'Respuesta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntasReflexion()
    {
        return $this->hasOne(IsmPreguntasReflexionPaiInterdisciplinar::className(), ['id' => 'id_preguntas_reflexion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaPlanInterPai()
    {
        return $this->hasOne(IsmRespuestaPlanInterdiciplinar::className(), ['id' => 'id_respuesta_plan_inter_pai']);
    }
}
