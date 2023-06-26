<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "toc_plan_unidad_aprendizaje".
 *
 * @property int $id
 * @property int $toc_plan_unidad_id
 * @property int $toc_opcion_id
 *
 * @property TocOpciones $tocOpcion
 * @property TocPlanUnidad $tocPlanUnidad
 */
class TocPlanUnidadAprendizaje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toc_plan_unidad_aprendizaje';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toc_plan_unidad_id', 'toc_opcion_id'], 'required'],
            [['toc_plan_unidad_id', 'toc_opcion_id'], 'default', 'value' => null],
            [['toc_plan_unidad_id', 'toc_opcion_id'], 'integer'],
            [['toc_opcion_id'], 'exist', 'skipOnError' => true, 'targetClass' => TocOpciones::className(), 'targetAttribute' => ['toc_opcion_id' => 'id']],
            [['toc_plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => TocPlanUnidad::className(), 'targetAttribute' => ['toc_plan_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'toc_plan_unidad_id' => 'Toc Plan Unidad ID',
            'toc_opcion_id' => 'Toc Opcion ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTocOpcion()
    {
        return $this->hasOne(TocOpciones::className(), ['id' => 'toc_opcion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTocPlanUnidad()
    {
        return $this->hasOne(TocPlanUnidad::className(), ['id' => 'toc_plan_unidad_id']);
    }
}
