<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "visita_aulica".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $estudiantes_asistidos
 * @property bool $aplica_grupal
 * @property string $psicologo_usuario
 * @property string $fecha
 * @property string $hora_inicio
 * @property string $hora_finalizacion
 * @property string $observaciones_al_docente
 * @property string $fecha_firma_dece
 * @property string $fecha_firma_docente
 *
 * @property ScholarisClase $clase
 * @property ScholarisClase $clase0
 * @property VisitasAulicasEstudiantes[] $visitasAulicasEstudiantes
 * @property VisitasAulicasObservacionesDocente[] $visitasAulicasObservacionesDocentes
 */
class VisitaAulica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visita_aulica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'estudiantes_asistidos', 'psicologo_usuario', 'fecha', 'hora_inicio', 'hora_finalizacion'], 'required'],
            [['clase_id', 'estudiantes_asistidos'], 'default', 'value' => null],
            [['clase_id', 'estudiantes_asistidos'], 'integer'],
            [['aplica_grupal'], 'boolean'],
            [['fecha', 'hora_inicio', 'hora_finalizacion', 'fecha_firma_dece', 'fecha_firma_docente'], 'safe'],
            [['observaciones_al_docente'], 'string'],
            [['psicologo_usuario'], 'string', 'max' => 200],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'estudiantes_asistidos' => 'Estudiantes Asistidos',
            'aplica_grupal' => 'Aplica Grupal',
            'psicologo_usuario' => 'Psicologo Usuario',
            'fecha' => 'Fecha',
            'hora_inicio' => 'Hora Inicio',
            'hora_finalizacion' => 'Hora Finalizacion',
            'observaciones_al_docente' => 'Observaciones Al Docente',
            'fecha_firma_dece' => 'Fecha Firma Dece',
            'fecha_firma_docente' => 'Fecha Firma Docente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase0()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitasAulicasEstudiantes()
    {
        return $this->hasMany(VisitasAulicasEstudiantes::className(), ['visita_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitasAulicasObservacionesDocentes()
    {
        return $this->hasMany(VisitasAulicasObservacionesDocente::className(), ['visita_id' => 'id']);
    }
}
