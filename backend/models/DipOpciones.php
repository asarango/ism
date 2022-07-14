<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dip_opciones".
 *
 * @property int $id
 * @property string $tipo
 * @property string $categoria
 * @property string $opcion
 * @property bool $estado
 */
class DipOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dip_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'opcion'], 'required'],
            [['categoria', 'opcion'], 'string'],
            [['estado'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo' => 'Tipo',
            'categoria' => 'Categoria',
            'opcion' => 'Opcion',
            'estado' => 'Estado',
        ];
    }
}
