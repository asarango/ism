<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_respuesta_contenido_pai_interdiciplinar2".
 *
 * @property int $id
 * @property int $id_respuesta_pai_interdisciplinar
 * @property int $id_contenido_pai
 * @property bool $mostrar
 * @property string $tipo
 * @property string $contenido
 *
 * @property IsmRespuestaPlanInterdiciplinar $respuestaPaiInterdisciplinar
 */
class IsmRespuestaContenidoPaiInterdiciplinar2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_respuesta_contenido_pai_interdiciplinar2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_respuesta_pai_interdisciplinar', 'id_contenido_pai', 'mostrar'], 'required'],
            [['id_respuesta_pai_interdisciplinar', 'id_contenido_pai'], 'default', 'value' => null],
            [['id_respuesta_pai_interdisciplinar', 'id_contenido_pai'], 'integer'],
            [['mostrar'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
            [['contenido'], 'string', 'max' => 100],
            [['id_respuesta_pai_interdisciplinar'], 'exist', 'skipOnError' => true, 'targetClass' => IsmRespuestaPlanInterdiciplinar::className(), 'targetAttribute' => ['id_respuesta_pai_interdisciplinar' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_respuesta_pai_interdisciplinar' => 'Id Respuesta Pai Interdisciplinar',
            'id_contenido_pai' => 'Id Contenido Pai',
            'mostrar' => 'Mostrar',
            'tipo' => 'Tipo',
            'contenido' => 'Contenido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaPaiInterdisciplinar()
    {
        return $this->hasOne(IsmRespuestaPlanInterdiciplinar::className(), ['id' => 'id_respuesta_pai_interdisciplinar']);
    }
}
