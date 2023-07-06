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
 * @property string $otra_area
 * @property string $acciones_responsables
 * @property int $id_caso
 *
 * @property DeceCasos $caso
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
            [['id_estudiante', 'fecha_intervencion', 'razon',  'acciones_responsables'], 'required'],
            [['id_estudiante','id_caso'], 'default', 'value' => null],
            [['id_estudiante','id_caso','numero_caso'], 'integer'],
            [['fecha_intervencion'], 'safe'],
            [['razon', 'acciones_responsables','objetivo_general'], 'string', 'max' => 2000],
            [['otra_area'], 'string', 'max' => 50],
            [['id_caso'], 'exist', 'skipOnError' => true, 'targetClass' => DeceCasos::className(), 'targetAttribute' => ['id_caso' => 'id']],
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
            'fecha_intervencion' => 'Fecha Intervención',
            'razon' => 'Razón',
            'otra_area' => 'Otra / Especifique:',
            'acciones_responsables' => 'Acciones / Responsables',
            'objetivo_general'=>'Objetivo General',
            'id_caso' => 'Id Caso',
            'numero_caso'=>'Número Caso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaso()
    {
        return $this->hasOne(DeceCasos::className(), ['id' => 'id_caso']);
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
