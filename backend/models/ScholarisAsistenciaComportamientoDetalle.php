<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_comportamiento_detalle".
 *
 * @property int $id
 * @property int $comportamiento_id
 * @property string $codigo
 * @property string $nombre
 * @property string $tipo
 * @property int $cantidad_descuento
 * @property string $punto_descuento
 * @property int $total_x_unidad
 * @property string $code_fj
 * @property bool $activo
 * @property int $limite
 *
 * @property ScholarisAsistenciaAlumnosNovedades[] $scholarisAsistenciaAlumnosNovedades
 * @property ScholarisAsistenciaComportamiento $comportamiento
 * @property ScholarisAsistenciaComportamientoFecuencia[] $scholarisAsistenciaComportamientoFecuencias
 */
class ScholarisAsistenciaComportamientoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_comportamiento_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comportamiento_id', 'codigo', 'nombre'], 'required'],
            [['comportamiento_id', 'cantidad_descuento', 'total_x_unidad', 'limite'], 'default', 'value' => null],
            [['comportamiento_id', 'cantidad_descuento', 'total_x_unidad', 'limite'], 'integer'],
            [['punto_descuento'], 'number'],
            [['activo'], 'boolean'],
            [['codigo'], 'string', 'max' => 5],
            [['nombre'], 'string', 'max' => 150],
            [['tipo'], 'string', 'max' => 10],
            [['code_fj'], 'string', 'max' => 30],
            [['comportamiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisAsistenciaComportamiento::className(), 'targetAttribute' => ['comportamiento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comportamiento_id' => 'Comportamiento ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'tipo' => 'Tipo',
            'cantidad_descuento' => 'Cantidad Descuento',
            'punto_descuento' => 'Punto Descuento',
            'total_x_unidad' => 'Total X Unidad',
            'code_fj' => 'Code Fj',
            'activo' => 'Activo',
            'limite' => 'Limite',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaAlumnosNovedades()
    {
        return $this->hasMany(ScholarisAsistenciaAlumnosNovedades::className(), ['comportamiento_detalle_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComportamiento()
    {
        return $this->hasOne(ScholarisAsistenciaComportamiento::className(), ['id' => 'comportamiento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaComportamientoFecuencias()
    {
        return $this->hasMany(ScholarisAsistenciaComportamientoFecuencia::className(), ['detalle_id' => 'id']);
    }
}
