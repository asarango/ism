<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mapa_enfoques_pai_aprobacion".
 *
 * @property int $id
 * @property int $materia_id
 * @property int $periodo_id
 * @property string $coordinador_usuario
 * @property string $jefe_area_usuario
 * @property string $fecha_envio_a_coordinado
 * @property string $fecha_aprobacion
 * @property string $estado
 *
 * @property IsmMateria $materia
 * @property ScholarisPeriodo $periodo
 * @property Usuario $coordinadorUsuario
 * @property Usuario $jefeAreaUsuario
 */
class MapaEnfoquesPaiAprobacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mapa_enfoques_pai_aprobacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['materia_id', 'periodo_id', 'coordinador_usuario', 'jefe_area_usuario'], 'required'],
            [['materia_id', 'periodo_id'], 'default', 'value' => null],
            [['materia_id', 'periodo_id'], 'integer'],
            [['fecha_envio_a_coordinado', 'fecha_aprobacion'], 'safe'],
            [['coordinador_usuario', 'jefe_area_usuario'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 20],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmMateria::className(), 'targetAttribute' => ['materia_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
            [['coordinador_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['coordinador_usuario' => 'usuario']],
            [['jefe_area_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['jefe_area_usuario' => 'usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'materia_id' => 'Materia ID',
            'periodo_id' => 'Periodo ID',
            'coordinador_usuario' => 'Coordinador Usuario',
            'jefe_area_usuario' => 'Jefe Area Usuario',
            'fecha_envio_a_coordinado' => 'Fecha Envio A Coordinado',
            'fecha_aprobacion' => 'Fecha Aprobacion',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(IsmMateria::className(), ['id' => 'materia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoordinadorUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'coordinador_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJefeAreaUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'jefe_area_usuario']);
    }
}
