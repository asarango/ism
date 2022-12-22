<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_grupo_plan_interdiciplinar".
 *
 * @property int $id
 * @property int $id_bloque
 * @property int $id_op_course
 * @property string $nombre_grupo
 * @property int $id_periodo
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property IsmGrupoMateriaPlanInterdiciplinar[] $ismGrupoMateriaPlanInterdiciplinars
 * @property OpCourse $opCourse
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisPeriodo $periodo
 * @property IsmRespuestaPlanInterdiciplinar[] $ismRespuestaPlanInterdiciplinars
 */
class IsmGrupoPlanInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_grupo_plan_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_bloque', 'id_op_course', 'nombre_grupo', 'id_periodo', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['id_bloque', 'id_op_course', 'id_periodo'], 'default', 'value' => null],
            [['id_bloque', 'id_op_course', 'id_periodo'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre_grupo', 'created', 'updated'], 'string', 'max' => 50],
            [['id_op_course'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['id_op_course' => 'id']],
            [['id_bloque'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['id_bloque' => 'id']],
            [['id_periodo'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['id_periodo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_bloque' => 'Id Bloque',
            'id_op_course' => 'Id Op Course',
            'nombre_grupo' => 'Nombre Grupo',
            'id_periodo' => 'Id Periodo',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmGrupoMateriaPlanInterdiciplinars()
    {
        return $this->hasMany(IsmGrupoMateriaPlanInterdiciplinar::className(), ['id_grupo_plan_inter' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourse()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'id_op_course']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'id_bloque']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'id_periodo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmRespuestaPlanInterdiciplinars()
    {
        return $this->hasMany(IsmRespuestaPlanInterdiciplinar::className(), ['id_grupo_plan_inter' => 'id']);
    }
}
