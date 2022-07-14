<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pep_planificacion_x_unidad".
 *
 * @property int $id
 * @property int $op_course_template_id
 * @property int $scholaris_periodo_id
 * @property int $bloque_id
 * @property int $tema_transdisciplinar_id
 * @property string $desde
 * @property string $hasta
 * @property int $porcentaje_planificado
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property OpCourseTemplate $opCourseTemplate
 * @property PepOpciones $temaTransdisciplinar
 * @property ScholarisPeriodo $scholarisPeriodo
 */
class PepPlanificacionXUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pep_planificacion_x_unidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['op_course_template_id', 'scholaris_periodo_id', 'tema_transdisciplinar_id', 'desde', 'hasta', 'porcentaje_planificado', 'created_at', 'created'], 'required'],
            [['op_course_template_id', 'scholaris_periodo_id', 'bloque_id', 'tema_transdisciplinar_id', 'porcentaje_planificado'], 'default', 'value' => null],
            [['op_course_template_id', 'scholaris_periodo_id', 'bloque_id', 'tema_transdisciplinar_id', 'porcentaje_planificado'], 'integer'],
            [['desde', 'hasta', 'created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['op_course_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['op_course_template_id' => 'id']],
            [['tema_transdisciplinar_id'], 'exist', 'skipOnError' => true, 'targetClass' => PepOpciones::className(), 'targetAttribute' => ['tema_transdisciplinar_id' => 'id']],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'op_course_template_id' => 'Op Course Template ID',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
            'bloque_id' => 'Bloque ID',
            'tema_transdisciplinar_id' => 'Tema Transdisciplinar ID',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'porcentaje_planificado' => 'Porcentaje Planificado',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
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
    public function getTemaTransdisciplinar()
    {
        return $this->hasOne(PepOpciones::className(), ['id' => 'tema_transdisciplinar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }
}
