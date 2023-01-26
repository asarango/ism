<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_respuesta_contenido_pai_interdiciplinar".
 *
 * @property int $id
 * @property int $id_respuesta_opciones_pai
 * @property int $id_contenido_pai
 * @property bool $mostrar
 * @property string $tipo
 * @property string $contenido
 *
 * @property IsmRespuestaOpcionesPaiInterdiciplinar $respuestaOpcionesPai
 */
class IsmRespuestaContenidoPaiInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_respuesta_contenido_pai_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_respuesta_opciones_pai', 'id_contenido_pai', 'mostrar'], 'required'],
            [['id_respuesta_opciones_pai', 'id_contenido_pai'], 'default', 'value' => null],
            [['id_respuesta_opciones_pai', 'id_contenido_pai'], 'integer'],
            [['mostrar'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
            [['contenido'], 'string', 'max' => 100],
            [['id_respuesta_opciones_pai'], 'exist', 'skipOnError' => true, 'targetClass' => IsmRespuestaOpcionesPaiInterdiciplinar::className(), 'targetAttribute' => ['id_respuesta_opciones_pai' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_respuesta_opciones_pai' => 'Id Respuesta Opciones Pai',
            'id_contenido_pai' => 'Id Contenido Pai',
            'mostrar' => 'Mostrar',
            'tipo' => 'Tipo',
            'contenido' => 'Contenido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaOpcionesPai()
    {
        return $this->hasOne(IsmRespuestaOpcionesPaiInterdiciplinar::className(), ['id' => 'id_respuesta_opciones_pai']);
    }
}
