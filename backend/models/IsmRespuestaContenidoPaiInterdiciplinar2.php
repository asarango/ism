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
 * @property string $actividad
 * @property string $objetivo
 * @property string $relacion_ods
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
            [['actividad', 'objetivo', 'relacion_ods'], 'string'],
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
            'actividad' => 'Actividad',
            'objetivo' => 'Objetivo',
            'relacion_ods' => 'Relacion Ods',
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
