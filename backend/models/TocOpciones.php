<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "toc_opciones".
 *
 * @property int $id
 * @property string $seccion
 * @property string $categoria
 * @property string $opcion
 * @property string $descripcion
 * @property string $tipo
 * @property bool $estado
 * @property string $planificacion
 */
class TocOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toc_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seccion', 'opcion', 'tipo', 'planificacion'], 'required'],
            [['descripcion'], 'string'],
            [['estado'], 'boolean'],
            [['seccion', 'categoria', 'tipo'], 'string', 'max' => 30],
            [['opcion'], 'string', 'max' => 80],
            [['planificacion'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seccion' => 'Seccion',
            'categoria' => 'Categoria',
            'opcion' => 'Opcion',
            'descripcion' => 'Descripcion',
            'tipo' => 'Tipo',
            'estado' => 'Estado',
            'planificacion' => 'Planificacion',
        ];
    }
}
