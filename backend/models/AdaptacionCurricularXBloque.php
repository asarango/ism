<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "adaptacion_curricular_x_bloque".
 *
 * @property int $id
 * @property int $id_nee_x_clase
 * @property string $adaptacion_curricular
 * @property string $creado_por
 * @property string $fecha_creacion
 * @property string $actualizado_por
 * @property string $fecha_actualizacion
 * @property int $id_curriculum_mec_bloque
 *
 * @property CurriculoMecBloque $curriculumMecBloque
 * @property NeeXClase $neeXClase
 */
class AdaptacionCurricularXBloque extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'adaptacion_curricular_x_bloque';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_nee_x_clase', 'id_curriculum_mec_bloque'], 'default', 'value' => null],
            [['id_nee_x_clase', 'id_curriculum_mec_bloque'], 'integer'],
            [['adaptacion_curricular'], 'string'],
            [['creado_por', 'fecha_creacion', 'actualizado_por', 'fecha_actualizacion'], 'required'],
            [['fecha_creacion', 'fecha_actualizacion'], 'safe'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 50],
            [['id_curriculum_mec_bloque'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecBloque::className(), 'targetAttribute' => ['id_curriculum_mec_bloque' => 'id']],
            [['id_nee_x_clase'], 'exist', 'skipOnError' => true, 'targetClass' => NeeXClase::className(), 'targetAttribute' => ['id_nee_x_clase' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_nee_x_clase' => 'Id Nee X Clase',
            'adaptacion_curricular' => 'Adaptacion Curricular',
            'creado_por' => 'Creado Por',
            'fecha_creacion' => 'Fecha Creacion',
            'actualizado_por' => 'Actualizado Por',
            'fecha_actualizacion' => 'Fecha Actualizacion',
            'id_curriculum_mec_bloque' => 'Id Curriculum Mec Bloque',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculumMecBloque()
    {
        return $this->hasOne(CurriculoMecBloque::className(), ['id' => 'id_curriculum_mec_bloque']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNeeXClase()
    {
        return $this->hasOne(NeeXClase::className(), ['id' => 'id_nee_x_clase']);
    }
}
