<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contenido_pai_opciones".
 *
 * @property int $id
 * @property string $tipo
 * @property string $contenido_es
 * @property string $contenido_en
 * @property string $contenido_fr
 * @property bool $estado
 * @property string $sub_contenido
 * @property string $sub_contenido_en
 * @property string $sub_contenido_fr
 */
class ContenidoPaiOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contenido_pai_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'contenido_es'], 'required'],
            [['contenido_es', 'contenido_en', 'contenido_fr'], 'string'],
            [['estado'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
            [['sub_contenido', 'sub_contenido_en', 'sub_contenido_fr'], 'string', 'max' => 500],
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
            'contenido_es' => 'Contenido Es',
            'contenido_en' => 'Contenido En',
            'contenido_fr' => 'Contenido Fr',
            'estado' => 'Estado',
            'sub_contenido' => 'Sub Contenido',
            'sub_contenido_en' => 'Sub Contenido En',
            'sub_contenido_fr' => 'Sub Contenido Fr',
        ];
    }
}
