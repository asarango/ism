<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_intervencion".
 *
 * @property int $id
 * @property int $id_estudiante
 * @property string $fecha_intervencion
 * @property string $razon
 * @property int $id_area
 * @property string $otra_area
 * @property string $acciones_responsables
 *
 * @property OpStudent $estudiante
 * @property DeceIntervencionAreaCompromiso[] $deceIntervencionAreaCompromisos
 * @property DeceIntervencionCompromiso[] $deceIntervencionCompromisos
 */
class DeceIntervencion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_intervencion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_estudiante', 'fecha_intervencion', 'razon', 'id_area', 'acciones_responsables'], 'required'],
            [['id_estudiante', 'id_area'], 'default', 'value' => null],
            [['id_estudiante', 'id_area'], 'integer'],
            [['fecha_intervencion'], 'safe'],
            [['razon', 'acciones_responsables'], 'string', 'max' => 2000],
            [['otra_area'], 'string', 'max' => 50],
            [['id_estudiante'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['id_estudiante' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_estudiante' => 'Id Estudiante',
            'fecha_intervencion' => 'Fecha Intervencion',
            'razon' => 'Razon',
            'id_area' => 'Id Area',
            'otra_area' => 'Otra Area',
            'acciones_responsables' => 'Acciones Responsables',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiante()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'id_estudiante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceIntervencionAreaCompromisos()
    {
        return $this->hasMany(DeceIntervencionAreaCompromiso::className(), ['id_dece_intervencion' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceIntervencionCompromisos()
    {
        return $this->hasMany(DeceIntervencionCompromiso::className(), ['id_dece_intervencion' => 'id']);
    }
}
