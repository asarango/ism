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
 * @property string $motivo
 * @property string $detalle
 * @property string $id_usuario
 * @property int $id_clase
 *
 * @property OpStudent $estudiante
 * @property ScholarisPeriodo $periodo
 * @property DeceDerivacion[] $deceDerivacions
 * @property DeceDeteccion[] $deceDeteccions
 * @property DeceIntervencion[] $deceIntervencions
 * @property DeceRegistroSeguimiento[] $deceRegistroSeguimientos
 * @property DeceRegistroSeguimiento[] $deceRegistroSeguimientos0
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
            [['numero_caso', 'id_estudiante', 'id_periodo', 'estado', 'fecha_inicio', 'motivo', 'detalle', 'id_usuario'], 'required'],
            [['numero_caso', 'id_estudiante', 'id_periodo', 'id_clase'], 'default', 'value' => null],
            [['numero_caso', 'id_estudiante', 'id_periodo', 'id_clase'], 'integer'],
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
            'numero_caso' => 'Numero Caso',
            'id_estudiante' => 'Id Estudiante',
            'id_periodo' => 'Id Periodo',
            'estado' => 'Estado',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'motivo' => 'Motivo',
            'detalle' => 'Detalle',
            'id_usuario' => 'Id Usuario',
            'id_clase' => 'Id Clase',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceDerivacions()
    {
        return $this->hasMany(DeceDerivacion::className(), ['id_casos' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceDeteccions()
    {
        return $this->hasMany(DeceDeteccion::className(), ['id_caso' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceIntervencions()
    {
        return $this->hasMany(DeceIntervencion::className(), ['id_caso' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceRegistroSeguimientos()
    {
        return $this->hasMany(DeceRegistroSeguimiento::className(), ['id_caso' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceRegistroSeguimientos0()
    {
        return $this->hasMany(DeceRegistroSeguimiento::className(), ['id_caso' => 'id']);
    }
}
