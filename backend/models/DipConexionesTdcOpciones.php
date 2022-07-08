<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dip_conexiones_tdc_opciones".
 *
 * @property int $id
 * @property bool $es_de_lectura
 * @property string $tipo_area
 * @property string $opcion
 * @property bool $es_activo
 *
 * @property PlanificacionConexionTdc[] $planificacionConexionTdcs
 */
class DipConexionesTdcOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dip_conexiones_tdc_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['es_de_lectura', 'es_activo'], 'boolean'],
            [['tipo_area'], 'required'],
            [['opcion'], 'string'],
            [['tipo_area'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'es_de_lectura' => 'Es De Lectura',
            'tipo_area' => 'Tipo Area',
            'opcion' => 'Opcion',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionConexionTdcs()
    {
        return $this->hasMany(PlanificacionConexionTdc::className(), ['opcion_tdc_id' => 'id']);
    }
}
