<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_comparte".
 *
 * @property int $id
 * @property string $nombre
 * @property int $valor
 * @property int $instituto_id
 * @property int $comparte_id
 * @property int $op_course_template_id
 *
 * @property OpCourseTemplate $opCourseTemplate
 * @property OpInstitute $instituto
 */
class ScholarisBloqueComparte extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_comparte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'valor'], 'required'],
            [['valor', 'instituto_id', 'comparte_id', 'op_course_template_id'], 'default', 'value' => null],
            [['valor', 'instituto_id', 'comparte_id', 'op_course_template_id'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['op_course_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['op_course_template_id' => 'id']],
            [['instituto_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['instituto_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'valor' => 'Valor',
            'instituto_id' => 'Instituto ID',
            'comparte_id' => 'Comparte ID',
            'op_course_template_id' => 'Op Course Template ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'op_course_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituto()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'instituto_id']);
    }
}
