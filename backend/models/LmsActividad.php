<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lms_actividad".
 *
 * @property int $id
 * @property int $lms_id
 * @property int $tipo_actividad_id
 * @property string $titulo
 * @property string $descripcion
 * @property string $tarea
 * @property string $material_apoyo
 * @property bool $es_calificado
 * @property bool $es_publicado
 * @property bool $es_aprobado
 * @property string $retroalimentacion
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property Lms $lms
 * @property ScholarisTipoActividad $tipoActividad
 * @property LmsActividadCriteriosPai[] $lmsActividadCriteriosPais
 * @property LmsActividadXArchivo[] $lmsActividadXArchivos
 */
class LmsActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lms_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lms_id', 'tipo_actividad_id', 'titulo', 'descripcion', 'created', 'created_at', 'updated', 'updated_at'], 'required'],
            [['lms_id', 'tipo_actividad_id'], 'default', 'value' => null],
            [['lms_id', 'tipo_actividad_id'], 'integer'],
            [['descripcion', 'tarea', 'material_apoyo', 'retroalimentacion'], 'string'],
            [['es_calificado', 'es_publicado', 'es_aprobado'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['titulo'], 'string', 'max' => 150],
            [['created', 'updated'], 'string', 'max' => 200],
            [['lms_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lms::className(), 'targetAttribute' => ['lms_id' => 'id']],
            [['tipo_actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisTipoActividad::className(), 'targetAttribute' => ['tipo_actividad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lms_id' => 'Lms ID',
            'tipo_actividad_id' => 'Tipo Actividad ID',
            'titulo' => 'Titulo',
            'descripcion' => 'Descripcion',
            'tarea' => 'Tarea',
            'material_apoyo' => 'Material Apoyo',
            'es_calificado' => 'Es Calificado',
            'es_publicado' => 'Es Publicado',
            'es_aprobado' => 'Es Aprobado',
            'retroalimentacion' => 'Retroalimentacion',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLms()
    {
        return $this->hasOne(Lms::className(), ['id' => 'lms_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoActividad()
    {
        return $this->hasOne(ScholarisTipoActividad::className(), ['id' => 'tipo_actividad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsActividadCriteriosPais()
    {
        return $this->hasMany(LmsActividadCriteriosPai::className(), ['lms_actividad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsActividadXArchivos()
    {
        return $this->hasMany(LmsActividadXArchivo::className(), ['lms_actividad_id' => 'id']);
    }
}
