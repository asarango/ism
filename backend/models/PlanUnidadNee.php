<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_unidad_nee".
 *
 * @property int $id
 * @property int $nee_x_unidad_id
 * @property int $curriculo_bloque_unidad_id
 * @property string $destrezas
 * @property string $actividades
 * @property string $recursos
 * @property string $indicadores_evaluacion
 * @property string $tecnicas_instrumentos
 * @property string $detalle_pai_dip
 *
 * @property CurriculoMecBloque $curriculoBloqueUnidad
 * @property NeeXClase $neeXUnidad
 */
class PlanUnidadNee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_unidad_nee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nee_x_unidad_id', 'curriculo_bloque_unidad_id'], 'required'],
            [['nee_x_unidad_id', 'curriculo_bloque_unidad_id'], 'default', 'value' => null],
            [['nee_x_unidad_id', 'curriculo_bloque_unidad_id'], 'integer'],
            [['destrezas', 'actividades', 'recursos', 'indicadores_evaluacion', 'tecnicas_instrumentos', 'detalle_pai_dip'], 'string'],
            [['curriculo_bloque_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecBloque::className(), 'targetAttribute' => ['curriculo_bloque_unidad_id' => 'id']],
            [['nee_x_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => NeeXClase::className(), 'targetAttribute' => ['nee_x_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nee_x_unidad_id' => 'Nee X Unidad ID',
            'curriculo_bloque_unidad_id' => 'Curriculo Bloque Unidad ID',
            'destrezas' => 'Destrezas',
            'actividades' => 'Actividades',
            'recursos' => 'Recursos',
            'indicadores_evaluacion' => 'Indicadores Evaluacion',
            'tecnicas_instrumentos' => 'Tecnicas Instrumentos',
            'detalle_pai_dip' => 'Detalle Pai Dip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculoBloqueUnidad()
    {
        return $this->hasOne(CurriculoMecBloque::className(), ['id' => 'curriculo_bloque_unidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNeeXUnidad()
    {
        return $this->hasOne(NeeXClase::className(), ['id' => 'nee_x_unidad_id']);
    }
}
