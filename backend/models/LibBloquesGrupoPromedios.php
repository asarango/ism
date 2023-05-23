<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lib_bloques_grupo_promedios".
 *
 * @property int $student_id
 * @property int $bloque_id
 * @property string $nota
 * @property string $abreviatura
 * @property int $periodo_id
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property OpStudent $student
 * @property ScholarisPeriodo $periodo
 */
class LibBloquesGrupoPromedios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lib_bloques_grupo_promedios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'bloque_id', 'periodo_id', 'created_at', 'created'], 'required'],
            [['student_id', 'bloque_id', 'periodo_id'], 'default', 'value' => null],
            [['student_id', 'bloque_id', 'periodo_id'], 'integer'],
            [['nota'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['abreviatura'], 'string', 'max' => 10],
            [['created', 'updated'], 'string', 'max' => 200],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_id' => 'Student ID',
            'bloque_id' => 'Bloque ID',
            'nota' => 'Nota',
            'abreviatura' => 'Abreviatura',
            'periodo_id' => 'Periodo ID',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
