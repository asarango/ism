<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_pai_opciones".
 *
 * @property int $id
 * @property int $plan_unidad_id
 * @property string $tipo
 * @property string $contenido
 * @property int $id_relacion
 * @property string $tipo2
 * @property string $actividad
 * @property int $id_pudpai_perfil
 * @property string $sub_contenido
 *
 * @property IsmRespuestaOpcionesPaiInterdiciplinar[] $ismRespuestaOpcionesPaiInterdiciplinars
 * @property PlanificacionBloquesUnidad $planUnidad
 */
class PlanificacionVerticalPaiOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_pai_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_unidad_id', 'tipo', 'contenido'], 'required'],
            [['plan_unidad_id', 'id_relacion', 'id_pudpai_perfil'], 'default', 'value' => null],
            [['plan_unidad_id', 'id_relacion', 'id_pudpai_perfil'], 'integer'],
            [['contenido', 'actividad'], 'string'],
            [['tipo'], 'string', 'max' => 50],
            [['tipo2'], 'string', 'max' => 100],
            [['sub_contenido'], 'string', 'max' => 500],
            [['plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['plan_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_unidad_id' => 'Plan Unidad ID',
            'tipo' => 'Tipo',
            'contenido' => 'Contenido',
            'id_relacion' => 'Id Relacion',
            'tipo2' => 'Tipo2',
            'actividad' => 'Actividad',
            'id_pudpai_perfil' => 'Id Pudpai Perfil',
            'sub_contenido' => 'Sub Contenido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmRespuestaOpcionesPaiInterdiciplinars()
    {
        return $this->hasMany(IsmRespuestaOpcionesPaiInterdiciplinar::className(), ['id_plan_vert_opciones' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'plan_unidad_id']);
    }
}
