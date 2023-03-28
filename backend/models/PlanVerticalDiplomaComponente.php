<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_vertical_diploma_componente".
 *
 * @property int $id
 * @property int $cabecera_id
 * @property string $evaluacion
 * @property string $actividad
 * @property string $fecha
 * @property bool $revision_cumplimiento
 * @property string $user_revision
 * @property string $fecha_revision
 *
 * @property PlanificacionDesagregacionCabecera $cabecera
 */
class PlanVerticalDiplomaComponente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_vertical_diploma_componente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cabecera_id', 'evaluacion', 'actividad', 'fecha'], 'required'],
            [['cabecera_id'], 'default', 'value' => null],
            [['cabecera_id'], 'integer'],
            [['actividad'], 'string'],
            [['fecha', 'fecha_revision'], 'safe'],
            [['revision_cumplimiento'], 'boolean'],
            [['evaluacion'], 'string', 'max' => 20],
            [['user_revision'], 'string', 'max' => 200],
            [['cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCabecera::className(), 'targetAttribute' => ['cabecera_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cabecera_id' => 'Cabecera ID',
            'evaluacion' => 'Evaluacion',
            'actividad' => 'Actividad',
            'fecha' => 'Fecha',
            'revision_cumplimiento' => 'Revision Cumplimiento',
            'user_revision' => 'User Revision',
            'fecha_revision' => 'Fecha Revision',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCabecera()
    {
        return $this->hasOne(PlanificacionDesagregacionCabecera::className(), ['id' => 'cabecera_id']);
    }
}
