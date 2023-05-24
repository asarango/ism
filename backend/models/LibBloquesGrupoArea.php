<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lib_bloques_grupo_area".
 *
 * @property int $ism_malla_area_id
 * @property int $student_id
 * @property int $bloque_id
 * @property string $nota
 * @property bool $promedia
 * @property string $abreviatura
 * @property bool $imprime
 * @property string $porcentaje
 * @property string $tipo
 * @property int $periodo_id
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property IsmMallaArea $ismMallaArea
 * @property OpStudent $student
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisPeriodo $periodo
 */
class LibBloquesGrupoArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lib_bloques_grupo_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ism_malla_area_id', 'student_id', 'bloque_id', 'periodo_id', 'created_at', 'created'], 'required'],
            [['ism_malla_area_id', 'student_id', 'bloque_id', 'periodo_id'], 'default', 'value' => null],
            [['ism_malla_area_id', 'student_id', 'bloque_id', 'periodo_id'], 'integer'],
            [['nota', 'porcentaje'], 'number'],
            [['promedia', 'imprime'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['abreviatura'], 'string', 'max' => 10],
            [['tipo'], 'string', 'max' => 30],
            [['created', 'updated'], 'string', 'max' => 200],
            [['ism_malla_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmMallaArea::className(), 'targetAttribute' => ['ism_malla_area_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ism_malla_area_id' => 'Ism Malla Area ID',
            'student_id' => 'Student ID',
            'bloque_id' => 'Bloque ID',
            'nota' => 'Nota',
            'promedia' => 'Promedia',
            'abreviatura' => 'Abreviatura',
            'imprime' => 'Imprime',
            'porcentaje' => 'Porcentaje',
            'tipo' => 'Tipo',
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
    public function getIsmMallaArea()
    {
        return $this->hasOne(IsmMallaArea::className(), ['id' => 'ism_malla_area_id']);
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
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
