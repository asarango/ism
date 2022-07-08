<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_casos".
 *
 * @property int $id
 * @property int $numero_caso
 * @property int $id_estudiante
 * @property int $id_periodo
 * @property string $estado
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $detalle
 * @property int $id_usuario
 * @property string $motivo
 *
 * @property OpStudent $estudiante
 * @property ScholarisPeriodo $periodo
 */
class DeceCasos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_casos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_caso', 'id_estudiante', 'id_periodo', 'estado', 'fecha_inicio', 'detalle', 'id_usuario', 'motivo'], 'required'],
            [['numero_caso', 'id_estudiante', 'id_periodo', 'id_usuario'], 'default', 'value' => null],
            [['numero_caso', 'id_estudiante', 'id_periodo','id_clase'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['detalle'], 'string'],
            [['estado'], 'string', 'max' => 50],
            [['motivo', 'id_usuario'], 'string', 'max' => 100],
            [['id_estudiante'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['id_estudiante' => 'id']],
            [['id_periodo'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['id_periodo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_caso' => 'Número Caso',
            'id_estudiante' => 'Id Estudiante',
            'id_clase' => 'Id Clase',
            'id_periodo' => 'Id Periodo',
            'estado' => 'Estado',
            'fecha_inicio' => 'Fecha',
            'fecha_fin' => 'Fecha Mod',
            'detalle' => 'Detalle',
            'id_usuario' => 'Usuario',
            'motivo' => 'Motivo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiante()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'id_estudiante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'id_periodo']);
    }
}
