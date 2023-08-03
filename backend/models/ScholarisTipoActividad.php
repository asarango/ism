<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tipo_actividad".
 *
 * @property int $id
 * @property string $nombre_nacional Nombre
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property int $orden Orden
 * @property string $nombre_pai Nombre PAI
 * @property string $tipo
 * @property bool $activo
 * @property bool $es_insumo
 * @property string $categoria
 * @property string $tipo_aporte
 * @property string $porcentaje
 *
 * @property LmsActividad[] $lmsActividads
 */
class ScholarisTipoActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tipo_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_nacional'], 'required'],
            [['create_uid', 'write_uid', 'orden'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'orden'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['activo', 'es_insumo'], 'boolean'],
            [['porcentaje'], 'number'],
            [['nombre_nacional', 'nombre_pai'], 'string', 'max' => 50],
            [['tipo'], 'string', 'max' => 1],
            [['categoria'], 'string', 'max' => 30],
            [['tipo_aporte'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_nacional' => 'Nombre Nacional',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'orden' => 'Orden',
            'nombre_pai' => 'Nombre Pai',
            'tipo' => 'Tipo',
            'activo' => 'Activo',
            'es_insumo' => 'Es Insumo',
            'categoria' => 'Categoria',
            'tipo_aporte' => 'Tipo Aporte',
            'porcentaje' => 'Porcentaje',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsActividads()
    {
        return $this->hasMany(LmsActividad::className(), ['tipo_actividad_id' => 'id']);
    }
}
