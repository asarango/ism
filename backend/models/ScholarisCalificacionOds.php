<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificacion_ods".
 *
 * @property int $id
 * @property int $actividad_id
 * @property int $grupo_id
 * @property int $calificacion
 *
 * @property ScholarisActividad $actividad
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class ScholarisCalificacionOds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificacion_ods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'grupo_id', 'calificacion'], 'required'],
            [['actividad_id', 'grupo_id', 'calificacion'], 'default', 'value' => null],
            [['actividad_id', 'grupo_id', 'calificacion'], 'integer'],
            [['actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisActividad::className(), 'targetAttribute' => ['actividad_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'actividad_id' => 'Actividad ID',
            'grupo_id' => 'Grupo ID',
            'calificacion' => 'Calificacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActividad()
    {
        return $this->hasOne(ScholarisActividad::className(), ['id' => 'actividad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
