<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_derivacion".
 *
 * @property int $id
 * @property string $tipo_derivacion
 * @property int $id_estudiante
 * @property string $nombre_quien_deriva
 * @property string $fecha_derivacion
 * @property string $motivo_referencia
 * @property string $historia_situacion_actual
 * @property string $accion_desarrollada
 * @property string $tipo_ayuda
 * @property int $id_casos
 * @property int $numero_casos
 * @property string $fecha_modificacion
 * @property string $otra_institucion_externa
 *
 * @property DeceCasos $casos
 * @property OpStudent $estudiante
 * @property DeceDerivacionInstitucionExterna[] $deceDerivacionInstitucionExternas
 */
class DeceDerivacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_derivacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_estudiante', 'nombre_quien_deriva', 'fecha_derivacion', 'id_casos', 'numero_casos', 'fecha_modificacion', 'otra_institucion_externa'], 'required'],
            [['id_estudiante', 'id_casos', 'numero_casos'], 'default', 'value' => null],
            [['id_estudiante', 'id_casos', 'numero_casos'], 'integer'],
            [['nombre_quien_deriva', 'motivo_referencia', 'historia_situacion_actual', 'accion_desarrollada', 'tipo_ayuda'], 'string'],
            [['fecha_derivacion', 'fecha_modificacion'], 'safe'],
            [['tipo_derivacion'], 'string', 'max' => 50],
            [['otra_institucion_externa'], 'string', 'max' => 100],
            [['id_casos'], 'exist', 'skipOnError' => true, 'targetClass' => DeceCasos::className(), 'targetAttribute' => ['id_casos' => 'id']],
            [['id_estudiante'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['id_estudiante' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_derivacion' => 'Tipo Derivacion',
            'id_estudiante' => 'Id Estudiante',
            'nombre_quien_deriva' => 'Nombre Quien Deriva',
            'fecha_derivacion' => 'Fecha Derivacion',
            'motivo_referencia' => 'Motivo Referencia',
            'historia_situacion_actual' => 'Historia Situacion Actual',
            'accion_desarrollada' => 'Accion Desarrollada',
            'tipo_ayuda' => 'Tipo Ayuda',
            'id_casos' => 'Id Casos',
            'numero_casos' => 'Numero Casos',
            'fecha_modificacion' => 'Fecha Modificacion',
            'otra_institucion_externa' => 'Otra Institución Externa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCasos()
    {
        return $this->hasOne(DeceCasos::className(), ['id' => 'id_casos']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiante()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'id_estudiante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceDerivacionInstitucionExternas()
    {
        return $this->hasMany(DeceDerivacionInstitucionExterna::className(), ['id_dece_derivacion' => 'id']);
    }
}