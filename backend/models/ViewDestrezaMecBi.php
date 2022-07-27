<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_destreza_mec_bi".
 *
 * @property string $curso
 * @property int $op_course_template_id
 * @property string $materia
 * @property string $criterio_eval_codigo
 * @property string $criterio_eval_descripcion
 * @property int $destreza_id
 * @property string $destreza_codigo
 * @property string $destreza
 * @property int $detalle_id
 * @property string $detalle_destreza_id
 * @property int $pep_planificacion_unidad_id
 */
class ViewDestrezaMecBi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_destreza_mec_bi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso', 'criterio_eval_descripcion', 'destreza', 'detalle_destreza_id'], 'string'],
            [['op_course_template_id', 'destreza_id', 'detalle_id', 'pep_planificacion_unidad_id'], 'default', 'value' => null],
            [['op_course_template_id', 'destreza_id', 'detalle_id', 'pep_planificacion_unidad_id'], 'integer'],
            [['materia'], 'string', 'max' => 100],
            [['criterio_eval_codigo', 'destreza_codigo'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curso' => 'Curso',
            'op_course_template_id' => 'Op Course Template ID',
            'materia' => 'Materia',
            'criterio_eval_codigo' => 'Criterio Eval Codigo',
            'criterio_eval_descripcion' => 'Criterio Eval Descripcion',
            'destreza_id' => 'Destreza ID',
            'destreza_codigo' => 'Destreza Codigo',
            'destreza' => 'Destreza',
            'detalle_id' => 'Detalle ID',
            'detalle_destreza_id' => 'Detalle Destreza ID',
            'pep_planificacion_unidad_id' => 'Pep Planificacion Unidad ID',
        ];
    }
}
