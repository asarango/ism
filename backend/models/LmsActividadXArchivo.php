<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lms_actividad_x_archivo".
 *
 * @property int $id
 * @property int $lms_actividad_id
 * @property string $alias_archivo
 * @property string $archivo
 * @property int $path_ism_area_materia_id
 * @property bool $es_publicado
 *
 * @property LmsActividad $lmsActividad
 */
class LmsActividadXArchivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lms_actividad_x_archivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lms_actividad_id', 'alias_archivo', 'archivo', 'path_ism_area_materia_id'], 'required'],
            [['lms_actividad_id', 'path_ism_area_materia_id'], 'default', 'value' => null],
            [['lms_actividad_id', 'path_ism_area_materia_id'], 'integer'],
            [['es_publicado'], 'boolean'],
            [['alias_archivo', 'archivo'], 'string', 'max' => 80],
            [['lms_actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => LmsActividad::className(), 'targetAttribute' => ['lms_actividad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lms_actividad_id' => 'Lms Actividad ID',
            'alias_archivo' => 'Alias Archivo',
            'archivo' => 'Archivo',
            'path_ism_area_materia_id' => 'Path Ism Area Materia ID',
            'es_publicado' => 'Es Publicado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsActividad()
    {
        return $this->hasOne(LmsActividad::className(), ['id' => 'lms_actividad_id']);
    }
}
