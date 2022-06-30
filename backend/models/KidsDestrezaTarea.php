<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_destreza_tarea".
 *
 * @property int $id
 * @property int $plan_destreza_id
 * @property string $fecha_presentacion
 * @property string $titulo
 * @property string $detalle_tarea
 * @property string $materiales
 * @property bool $publicado_al_estudiante
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property KidsPlanSemanalHoraDestreza $planDestreza
 */
class KidsDestrezaTarea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_destreza_tarea';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_destreza_id', 'fecha_presentacion', 'titulo', 'detalle_tarea', 'created_at', 'created'], 'required'],
            [['plan_destreza_id'], 'default', 'value' => null],
            [['plan_destreza_id'], 'integer'],
            [['fecha_presentacion', 'created_at', 'updated_at'], 'safe'],
            [['detalle_tarea', 'materiales'], 'string'],
            [['publicado_al_estudiante'], 'boolean'],
            [['titulo'], 'string', 'max' => 100],
            [['created', 'updated'], 'string', 'max' => 200],
            [['plan_destreza_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsPlanSemanalHoraDestreza::className(), 'targetAttribute' => ['plan_destreza_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_destreza_id' => 'Plan Destreza ID',
            'fecha_presentacion' => 'Fecha Presentacion',
            'titulo' => 'Titulo',
            'detalle_tarea' => 'Detalle Tarea',
            'materiales' => 'Materiales',
            'publicado_al_estudiante' => 'Publicado Al Estudiante',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanDestreza()
    {
        return $this->hasOne(KidsPlanSemanalHoraDestreza::className(), ['id' => 'plan_destreza_id']);
    }
}
