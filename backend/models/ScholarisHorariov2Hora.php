<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_horariov2_hora".
 *
 * @property int $id
 * @property int $numero
 * @property string $sigla
 * @property string $nombre
 * @property string $desde
 * @property string $hasta
 * @property bool $es_receso
 *
 * @property ScholarisHorariov2Detalle[] $scholarisHorariov2Detalles
 * @property ScholarisLeccionarioDetalle[] $scholarisLeccionarioDetalles
 */
class ScholarisHorariov2Hora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_horariov2_hora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'sigla', 'nombre', 'desde', 'hasta'], 'required'],
            [['numero'], 'default', 'value' => null],
            [['numero'], 'integer'],
            [['es_receso'], 'boolean'],
            [['sigla'], 'string', 'max' => 20],
            [['nombre'], 'string', 'max' => 50],
            [['desde', 'hasta'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero' => 'Numero',
            'sigla' => 'Sigla',
            'nombre' => 'Nombre',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'es_receso' => 'Es Receso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisHorariov2Detalles()
    {
        return $this->hasMany(ScholarisHorariov2Detalle::className(), ['hora_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisLeccionarioDetalles()
    {
        return $this->hasMany(ScholarisLeccionarioDetalle::className(), ['hora_id' => 'id']);
    }
}
