<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_semanal_bitacora".
 *
 * @property int $id
 * @property int $semana_id
 * @property string $docente_usuario
 * @property int $curso_id
 * @property string $estado
 * @property string $obervaciones
 * @property string $fecha_envio
 * @property string $usuario_envia
 * @property string $usuario_recibe
 * @property string $fecha_recibe
 *
 * @property OpCourse $curso
 * @property ScholarisBloqueSemanas $semana
 * @property Usuario $docenteUsuario
 */
class PlanSemanalBitacora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_semanal_bitacora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semana_id', 'docente_usuario', 'curso_id', 'estado', 'fecha_envio', 'usuario_envia', 'usuario_recibe'], 'required'],
            [['semana_id', 'curso_id'], 'default', 'value' => null],
            [['semana_id', 'curso_id'], 'integer'],
            [['obervaciones'], 'string'],
            [['fecha_envio', 'fecha_recibe'], 'safe'],
            [['docente_usuario', 'usuario_envia', 'usuario_recibe'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 50],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_id' => 'id']],
            [['semana_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueSemanas::className(), 'targetAttribute' => ['semana_id' => 'id']],
            [['docente_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['docente_usuario' => 'usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'semana_id' => 'Semana ID',
            'docente_usuario' => 'Docente Usuario',
            'curso_id' => 'Curso ID',
            'estado' => 'Estado',
            'obervaciones' => 'Obervaciones',
            'fecha_envio' => 'Fecha Envio',
            'usuario_envia' => 'Usuario Envia',
            'usuario_recibe' => 'Usuario Recibe',
            'fecha_recibe' => 'Fecha Recibe',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'curso_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemana()
    {
        return $this->hasOne(ScholarisBloqueSemanas::className(), ['id' => 'semana_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'docente_usuario']);
    }
}
