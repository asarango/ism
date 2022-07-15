<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_califica_tarea".
 *
 * @property int $id
 * @property int $tarea_id
 * @property int $grupo_id
 * @property int $escala_id
 * @property bool $es_activo
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property KidsDestrezaTarea $tarea
 * @property KidsEscalaCalificacion $escala
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class KidsCalificaTarea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_califica_tarea';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tarea_id', 'grupo_id', 'escala_id', 'created_at', 'created'], 'required'],
            [['tarea_id', 'grupo_id', 'escala_id'], 'default', 'value' => null],
            [['tarea_id', 'grupo_id', 'escala_id'], 'integer'],
            [['es_activo'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['tarea_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDestrezaTarea::className(), 'targetAttribute' => ['tarea_id' => 'id']],
            [['escala_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsEscalaCalificacion::className(), 'targetAttribute' => ['escala_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tarea_id' => 'Tarea ID',
            'grupo_id' => 'Grupo ID',
            'escala_id' => 'Escala ID',
            'es_activo' => 'Es Activo',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarea()
    {
        return $this->hasOne(KidsDestrezaTarea::className(), ['id' => 'tarea_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEscala()
    {
        return $this->hasOne(KidsEscalaCalificacion::className(), ['id' => 'escala_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
