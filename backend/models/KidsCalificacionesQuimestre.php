<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_calificaciones_quimestre".
 *
 * @property int $id
 * @property int $quimestre_id
 * @property int $grupo_id
 * @property int $escala_id
 * @property int $destreza_id
 *
 * @property CurCurriculoDestreza $destreza
 * @property KidsEscalaCalificacion $escala
 * @property ScholarisGrupoAlumnoClase $grupo
 * @property ScholarisQuimestre $quimestre
 */
class KidsCalificacionesQuimestre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_calificaciones_quimestre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quimestre_id', 'grupo_id', 'escala_id', 'destreza_id'], 'required'],
            [['quimestre_id', 'grupo_id', 'escala_id', 'destreza_id'], 'default', 'value' => null],
            [['quimestre_id', 'grupo_id', 'escala_id', 'destreza_id'], 'integer'],
            [['destreza_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurCurriculoDestreza::className(), 'targetAttribute' => ['destreza_id' => 'id']],
            [['escala_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsEscalaCalificacion::className(), 'targetAttribute' => ['escala_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
            [['quimestre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisQuimestre::className(), 'targetAttribute' => ['quimestre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quimestre_id' => 'Quimestre ID',
            'grupo_id' => 'Grupo ID',
            'escala_id' => 'Escala ID',
            'destreza_id' => 'Destreza ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestreza()
    {
        return $this->hasOne(CurCurriculoDestreza::className(), ['id' => 'destreza_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuimestre()
    {
        return $this->hasOne(ScholarisQuimestre::className(), ['id' => 'quimestre_id']);
    }
}
