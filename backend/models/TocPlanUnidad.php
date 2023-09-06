<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "toc_plan_unidad".
 *
 * @property int $id
 * @property int $bloque_id
 * @property int $clase_id
 * @property string $titulo
 * @property string $objetivos
 * @property string $conceptos_clave
 * @property string $contenido
 * @property string $evaluacion_pd
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisClase $clase
 */
class TocPlanUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toc_plan_unidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bloque_id', 'clase_id', 'created', 'created_at', 'updated', 'updated_at'], 'required'],
            [['bloque_id', 'clase_id'], 'default', 'value' => null],
            [['bloque_id', 'clase_id'], 'integer'],
            [['titulo','objetivos', 'conceptos_clave', 'contenido', 'evaluacion_pd'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bloque_id' => 'Bloque ID',
            'clase_id' => 'Clase ID',
            'titulo' => 'Titulo',
            'objetivos' => 'Objetivos',
            'conceptos_clave' => 'Conceptos Clave',
            'contenido' => 'Contenido',
            'evaluacion_pd' => 'Evaluacion Pd',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
