<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "visitas_aulicas_estudiantes".
 *
 * @property int $id
 * @property int $visita_id
 * @property int $grupo_id
 * @property bool $es_presente
 * @property string $observaciones
 *
 * @property ScholarisGrupoAlumnoClase $grupo
 * @property VisitaAulica $visita
 */
class VisitasAulicasEstudiantes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitas_aulicas_estudiantes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visita_id', 'grupo_id', 'es_presente'], 'required'],
            [['visita_id', 'grupo_id'], 'default', 'value' => null],
            [['visita_id', 'grupo_id'], 'integer'],
            [['es_presente'], 'boolean'],
            [['observaciones'], 'string'],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
            [['visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitaAulica::className(), 'targetAttribute' => ['visita_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visita_id' => 'Visita ID',
            'grupo_id' => 'Grupo ID',
            'es_presente' => 'Es Presente',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisita()
    {
        return $this->hasOne(VisitaAulica::className(), ['id' => 'visita_id']);
    }
}
