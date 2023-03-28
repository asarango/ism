<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_vertical_diploma".
 *
 * @property int $id
 * @property int $cabecera_id
 * @property string $tipo_seccion
 * @property string $tipo_campo
 * @property string $opcion_texto
 * @property bool $opcion_seleccion
 * @property bool $position_flag
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property PlanificacionDesagregacionCabecera $cabecera
 */
class PlanVerticalDiploma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_vertical_diploma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cabecera_id', 'tipo_seccion', 'tipo_campo'], 'required'],
            [['cabecera_id'], 'default', 'value' => null],
            [['cabecera_id'], 'integer'],
            [['opcion_texto'], 'string'],
            [['opcion_seleccion', 'position_flag'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['tipo_seccion', 'tipo_campo'], 'string', 'max' => 30],
            [['created', 'updated'], 'string', 'max' => 200],
            [['cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCabecera::className(), 'targetAttribute' => ['cabecera_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cabecera_id' => 'Cabecera ID',
            'tipo_seccion' => 'Tipo Seccion',
            'tipo_campo' => 'Tipo Campo',
            'opcion_texto' => 'Opcion Texto',
            'opcion_seleccion' => 'Opcion Seleccion',
            'position_flag' => 'Position Flag',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCabecera()
    {
        return $this->hasOne(PlanificacionDesagregacionCabecera::className(), ['id' => 'cabecera_id']);
    }
}
