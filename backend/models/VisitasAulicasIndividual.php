<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "visitas_aulicas_individual".
 *
 * @property int $id
 * @property int $visita_estudiante_id
 * @property int $catalogo_id
 * @property bool $respuesta
 * @property string $observaciones
 *
 * @property VisitasAulicasCatalogo $catalogo
 * @property VisitasAulicasEstudiantes $visitaEstudiante
 */
class VisitasAulicasIndividual extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitas_aulicas_individual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visita_estudiante_id', 'catalogo_id'], 'required'],
            [['visita_estudiante_id', 'catalogo_id'], 'default', 'value' => null],
            [['visita_estudiante_id', 'catalogo_id'], 'integer'],
            [['respuesta'], 'boolean'],
            [['observaciones'], 'string'],
            [['catalogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitasAulicasCatalogo::className(), 'targetAttribute' => ['catalogo_id' => 'id']],
            [['visita_estudiante_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitasAulicasEstudiantes::className(), 'targetAttribute' => ['visita_estudiante_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visita_estudiante_id' => 'Visita Estudiante ID',
            'catalogo_id' => 'Catalogo ID',
            'respuesta' => 'Respuesta',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogo()
    {
        return $this->hasOne(VisitasAulicasCatalogo::className(), ['id' => 'catalogo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitaEstudiante()
    {
        return $this->hasOne(VisitasAulicasEstudiantes::className(), ['id' => 'visita_estudiante_id']);
    }
}
