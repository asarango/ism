<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_grupo_materia_plan_interdiciplinar".
 *
 * @property int $id
 * @property int $id_grupo_plan_inter
 * @property int $id_ism_area_materia
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property IsmAreaMateria $ismAreaMateria
 * @property IsmGrupoPlanInterdiciplinar $grupoPlanInter
 */
class IsmGrupoMateriaPlanInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_grupo_materia_plan_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo_plan_inter', 'id_ism_area_materia', 'created_at', 'created'], 'required'],
            [['id_grupo_plan_inter', 'id_ism_area_materia'], 'default', 'value' => null],
            [['id_grupo_plan_inter', 'id_ism_area_materia'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 50],
            [['id_ism_area_materia'], 'exist', 'skipOnError' => true, 'targetClass' => IsmAreaMateria::className(), 'targetAttribute' => ['id_ism_area_materia' => 'id']],
            [['id_grupo_plan_inter'], 'exist', 'skipOnError' => true, 'targetClass' => IsmGrupoPlanInterdiciplinar::className(), 'targetAttribute' => ['id_grupo_plan_inter' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_grupo_plan_inter' => 'Id Grupo Plan Inter',
            'id_ism_area_materia' => 'Id Ism Area Materia',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMateria()
    {
        return $this->hasOne(IsmAreaMateria::className(), ['id' => 'id_ism_area_materia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPlanInter()
    {
        return $this->hasOne(IsmGrupoPlanInterdiciplinar::className(), ['id' => 'id_grupo_plan_inter']);
    }
}
