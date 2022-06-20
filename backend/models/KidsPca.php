<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_pca".
 *
 * @property int $id
 * @property int $op_course_id
 * @property int $carga_horaria_semanal
 * @property int $numero_semanas_trabajo
 * @property int $imprevistos
 * @property string $objetivos
 * @property string $observaciones
 * @property string $bibliografia
 * @property string $estado
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property OpCourse $opCourse
 * @property KidsUnidadMicro[] $kidsUnidadMicros
 */
class KidsPca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_pca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['op_course_id', 'estado', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['op_course_id', 'carga_horaria_semanal', 'numero_semanas_trabajo', 'imprevistos'], 'default', 'value' => null],
            [['op_course_id', 'carga_horaria_semanal', 'numero_semanas_trabajo', 'imprevistos'], 'integer'],
            [['objetivos', 'observaciones', 'bibliografia'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['estado'], 'string', 'max' => 20],
            [['created', 'updated'], 'string', 'max' => 200],
            [['op_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['op_course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'op_course_id' => 'Op Course ID',
            'carga_horaria_semanal' => 'Carga Horaria Semanal',
            'numero_semanas_trabajo' => 'Numero Semanas Trabajo',
            'imprevistos' => 'Imprevistos',
            'objetivos' => 'Objetivos',
            'observaciones' => 'Observaciones',
            'bibliografia' => 'Bibliografia',
            'estado' => 'Estado',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourse()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'op_course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidsUnidadMicros()
    {
        return $this->hasMany(KidsUnidadMicro::className(), ['pca_id' => 'id']);
    }
}
