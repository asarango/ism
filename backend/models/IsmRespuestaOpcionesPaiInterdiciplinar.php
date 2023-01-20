<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_respuesta_opciones_pai_interdiciplinar".
 *
 * @property int $id
 * @property int $id_respuesta_plan_inter_pai
 * @property int $id_plan_vert_opciones
 * @property bool $mostrar
 * @property string $tipo
 * @property string $contenido
 *
 * @property IsmRespuestaPlanInterdiciplinar $respuestaPlanInterPai
 * @property PlanificacionVerticalPaiOpciones $planVertOpciones
 */
class IsmRespuestaOpcionesPaiInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_respuesta_opciones_pai_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_respuesta_plan_inter_pai', 'id_plan_vert_opciones', 'mostrar'], 'required'],
            [['id_respuesta_plan_inter_pai', 'id_plan_vert_opciones'], 'default', 'value' => null],
            [['id_respuesta_plan_inter_pai', 'id_plan_vert_opciones'], 'integer'],
            [['mostrar'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
            [['contenido'], 'string', 'max' => 100],
            [['id_respuesta_plan_inter_pai'], 'exist', 'skipOnError' => true, 'targetClass' => IsmRespuestaPlanInterdiciplinar::className(), 'targetAttribute' => ['id_respuesta_plan_inter_pai' => 'id']],
            [['id_plan_vert_opciones'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionVerticalPaiOpciones::className(), 'targetAttribute' => ['id_plan_vert_opciones' => 'id']],
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
            'id_plan_vert_opciones' => 'Id Plan Vert Opciones',
            'mostrar' => 'Mostrar',
            'tipo' => 'Tipo',
            'contenido' => 'Contenido',
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
    public function getPlanVertOpciones()
    {
        return $this->hasOne(PlanificacionVerticalPaiOpciones::className(), ['id' => 'id_plan_vert_opciones']);
    }
}
