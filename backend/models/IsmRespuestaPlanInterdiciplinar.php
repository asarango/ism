<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_respuesta_plan_interdiciplinar".
 *
 * @property int $id
 * @property int $id_grupo_plan_inter
 * @property int $id_contenido_plan_inter
 * @property string $respuesta
 *
 * @property IsmContenidoPlanInterdiciplinar $contenidoPlanInter
 * @property IsmGrupoPlanInterdiciplinar $grupoPlanInter
 */
class IsmRespuestaPlanInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_respuesta_plan_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo_plan_inter', 'id_contenido_plan_inter', 'respuesta'], 'required'],
            [['id_grupo_plan_inter', 'id_contenido_plan_inter'], 'default', 'value' => null],
            [['id_grupo_plan_inter', 'id_contenido_plan_inter'], 'integer'],
            [['respuesta'], 'string'],
            [['id_contenido_plan_inter'], 'exist', 'skipOnError' => true, 'targetClass' => IsmContenidoPlanInterdiciplinar::className(), 'targetAttribute' => ['id_contenido_plan_inter' => 'id']],
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
            'id_contenido_plan_inter' => 'Id Contenido Plan Inter',
            'respuesta' => 'Respuesta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContenidoPlanInter()
    {
        return $this->hasOne(IsmContenidoPlanInterdiciplinar::className(), ['id' => 'id_contenido_plan_inter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPlanInter()
    {
        return $this->hasOne(IsmGrupoPlanInterdiciplinar::className(), ['id' => 'id_grupo_plan_inter']);
    }
}
