<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_intervencion_compromiso".
 *
 * @property int $id
 * @property int $id_dece_intervencion
 * @property string $comp_estudiante
 * @property string $comp_representante
 * @property string $comp_docente
 * @property string $comp_dece
 * @property string $fecha_max_cumplimiento
 * @property string $revision_compromiso
 * @property bool $esaprobado
 *
 * @property DeceIntervencion $deceIntervencion
 */
class DeceIntervencionCompromiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_intervencion_compromiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dece_intervencion', 'fecha_max_cumplimiento', 'revision_compromiso', 'esaprobado'], 'required'],
            [['id_dece_intervencion'], 'default', 'value' => null],
            [['id_dece_intervencion'], 'integer'],
            [['fecha_max_cumplimiento'], 'safe'],
            [['esaprobado'], 'boolean'],
            [['comp_estudiante', 'comp_representante', 'comp_docente', 'comp_dece', 'revision_compromiso'], 'string', 'max' => 2000],
            [['id_dece_intervencion'], 'exist', 'skipOnError' => true, 'targetClass' => DeceIntervencion::className(), 'targetAttribute' => ['id_dece_intervencion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dece_intervencion' => 'Id Dece Intervencion',
            'comp_estudiante' => 'Compromiso Estudiante',
            'comp_representante' => 'Compromiso Representante',
            'comp_docente' => 'Compromiso Docente',
            'comp_dece' => 'Compromiso Dece',
            'fecha_max_cumplimiento' => 'Fecha Max Cumplimiento',
            'revision_compromiso' => 'Revisión Compromiso',
            'esaprobado' => 'Esaprobado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceIntervencion()
    {
        return $this->hasOne(DeceIntervencion::className(), ['id' => 'id_dece_intervencion']);
    }
}
