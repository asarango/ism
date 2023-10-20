<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lib_promedios_individual_grupal".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $bloque_id
 * @property string $tipo_aporte
 * @property int $periodo_id
 * @property string $promedio_normal
 * @property string $promedio_transformado
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisGrupoAlumnoClase $grupo
 * @property ScholarisPeriodo $periodo
 */
class LibPromediosIndividualGrupal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lib_promedios_individual_grupal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id', 'bloque_id', 'tipo_aporte', 'periodo_id', 'promedio_normal', 'promedio_transformado', 'created_at', 'created'], 'required'],
            [['grupo_id', 'bloque_id', 'periodo_id'], 'default', 'value' => null],
            [['grupo_id', 'bloque_id', 'periodo_id'], 'integer'],
            [['promedio_normal', 'promedio_transformado'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['tipo_aporte'], 'string', 'max' => 15],
            [['created', 'updated'], 'string', 'max' => 200],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo_id' => 'Grupo ID',
            'bloque_id' => 'Bloque ID',
            'tipo_aporte' => 'Tipo Aporte',
            'periodo_id' => 'Periodo ID',
            'promedio_normal' => 'Promedio Normal',
            'promedio_transformado' => 'Promedio Transformado',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
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
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
