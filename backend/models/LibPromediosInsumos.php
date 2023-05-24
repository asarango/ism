<?php

namespace backend\models;

use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisPeriodo;
use Yii;

/**
 * This is the model class for table "lib_promedios_insumos".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $bloque_id
 * @property int $grupo_calificacion
 * @property string $nota
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 * @property int $periodo_id
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisGrupoAlumnoClase $grupo
 * @property ScholarisPeriodo $periodo
 */
class LibPromediosInsumos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lib_promedios_insumos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id', 'bloque_id', 'grupo_calificacion', 'created_at', 'created'], 'required'],
            [['grupo_id', 'bloque_id', 'grupo_calificacion', 'periodo_id'], 'default', 'value' => null],
            [['grupo_id', 'bloque_id', 'grupo_calificacion', 'periodo_id'], 'integer'],
            [['nota'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'grupo_calificacion' => 'Grupo Calificacion',
            'nota' => 'Nota',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
            'periodo_id' => 'Periodo ID',
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
