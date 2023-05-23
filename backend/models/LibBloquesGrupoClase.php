<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lib_bloques_grupo_clase".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $bloque_id
 * @property string $nota
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 * @property int $periodo_id
 * @property string $abreviatura
 * @property bool $promedia
 * @property bool $imprime
 * @property string $porcentaje
 * @property string $tipo
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisGrupoAlumnoClase $grupo
 * @property ScholarisPeriodo $periodo
 */
class LibBloquesGrupoClase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lib_bloques_grupo_clase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id', 'bloque_id', 'created_at', 'created'], 'required'],
            [['grupo_id', 'bloque_id', 'periodo_id'], 'default', 'value' => null],
            [['grupo_id', 'bloque_id', 'periodo_id'], 'integer'],
            [['nota', 'porcentaje'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['promedia', 'imprime'], 'boolean'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['abreviatura'], 'string', 'max' => 10],
            [['tipo'], 'string', 'max' => 30],
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
            'nota' => 'Nota',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
            'periodo_id' => 'Periodo ID',
            'abreviatura' => 'Abreviatura',
            'promedia' => 'Promedia',
            'imprime' => 'Imprime',
            'porcentaje' => 'Porcentaje',
            'tipo' => 'Tipo',
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
