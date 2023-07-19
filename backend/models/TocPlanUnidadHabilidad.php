<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "toc_plan_unidad_habilidad".
 *
 * @property int $id
 * @property int $toc_plan_unidad_id
 * @property int $toc_opciones_id
 * @property bool $is_active
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property TocOpciones $tocOpciones
 * @property TocPlanUnidad $tocPlanUnidad
 */
class TocPlanUnidadHabilidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toc_plan_unidad_habilidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toc_plan_unidad_id', 'toc_opciones_id', 'created', 'created_at', 'updated', 'updated_at'], 'required'],
            [['toc_plan_unidad_id', 'toc_opciones_id'], 'default', 'value' => null],
            [['toc_plan_unidad_id', 'toc_opciones_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['toc_opciones_id'], 'exist', 'skipOnError' => true, 'targetClass' => TocOpciones::className(), 'targetAttribute' => ['toc_opciones_id' => 'id']],
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
            'toc_opciones_id' => 'Toc Opciones ID',
            'is_active' => 'Is Active',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTocOpciones()
    {
        return $this->hasOne(TocOpciones::className(), ['id' => 'toc_opciones_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTocPlanUnidad()
    {
        return $this->hasOne(TocPlanUnidad::className(), ['id' => 'toc_plan_unidad_id']);
    }
}
