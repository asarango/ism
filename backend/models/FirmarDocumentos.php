<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "firmar_documentos".
 *
 * @property int $id
 * @property string $tabla_source
 * @property int $documento_id
 * @property string $nombre
 * @property string $cargo
 * @property string $cedula
 * @property string $fecha_firma
 * @property string $tipo
 */
class FirmarDocumentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmar_documentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tabla_source', 'documento_id', 'nombre', 'fecha_firma', 'tipo'], 'required'],
            [['documento_id'], 'default', 'value' => null],
            [['documento_id'], 'integer'],
            [['fecha_firma'], 'safe'],
            [['tabla_source'], 'string', 'max' => 100],
            [['nombre'], 'string', 'max' => 150],
            [['cargo'], 'string', 'max' => 40],
            [['cedula'], 'string', 'max' => 15],
            [['tipo'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tabla_source' => 'Tabla Source',
            'documento_id' => 'Documento ID',
            'nombre' => 'Nombre',
            'cargo' => 'Cargo',
            'cedula' => 'Cedula',
            'fecha_firma' => 'Fecha Firma',
            'tipo' => 'Tipo',
        ];
    }
}
