<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "toc_plan_vertical".
 *
 * @property int $id
 * @property int $clase_id
 * @property string $opcion_descripcion
 * @property string $contenido
 * @property string $tipo
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property ScholarisClase $clase
 */
class TocPlanVertical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toc_plan_vertical';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'opcion_descripcion', 'created_at', 'updated_at'], 'required'],
            [['clase_id'], 'default', 'value' => null],
            [['clase_id'], 'integer'],
            [['opcion_descripcion', 'contenido'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['tipo'], 'string', 'max' => 20],
            [['created', 'updated'], 'string', 'max' => 200],
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
            'clase_id' => 'Clase ID',
            'opcion_descripcion' => 'Opcion Descripcion',
            'contenido' => 'Contenido',
            'tipo' => 'Tipo',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
