<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lms_docente_nee".
 *
 * @property int $id
 * @property int $lms_docente_id
 * @property int $nee_x_clase_id
 * @property string $adaptacion_curricular
 *
 * @property LmsDocente $lmsDocente
 * @property NeeXClase $neeXClase
 */
class LmsDocenteNee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lms_docente_nee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lms_docente_id', 'nee_x_clase_id'], 'required'],
            [['lms_docente_id', 'nee_x_clase_id'], 'default', 'value' => null],
            [['lms_docente_id', 'nee_x_clase_id'], 'integer'],
            [['adaptacion_curricular'], 'string'],
            [['lms_docente_id'], 'exist', 'skipOnError' => true, 'targetClass' => LmsDocente::className(), 'targetAttribute' => ['lms_docente_id' => 'id']],
            [['nee_x_clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => NeeXClase::className(), 'targetAttribute' => ['nee_x_clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lms_docente_id' => 'Lms Docente ID',
            'nee_x_clase_id' => 'Nee X Clase ID',
            'adaptacion_curricular' => 'Adaptacion Curricular',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsDocente()
    {
        return $this->hasOne(LmsDocente::className(), ['id' => 'lms_docente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNeeXClase()
    {
        return $this->hasOne(NeeXClase::className(), ['id' => 'nee_x_clase_id']);
    }
}
