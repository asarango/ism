<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_contenido_plan_interdiciplinar".
 *
 * @property int $id
 * @property int $id_seccion_interdiciplinar
 * @property string $nombre_campo
 * @property bool $activo
 * @property bool $heredado
 *
 * @property IsmSeccionPlanInterdiciplinar $seccionInterdiciplinar
 * @property IsmRespuestaPlanInterdiciplinar[] $ismRespuestaPlanInterdiciplinars
 */
class IsmContenidoPlanInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_contenido_plan_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_seccion_interdiciplinar', 'activo', 'heredado'], 'required'],
            [['id_seccion_interdiciplinar'], 'default', 'value' => null],
            [['id_seccion_interdiciplinar'], 'integer'],
            [['activo', 'heredado'], 'boolean'],
            [['nombre_campo'], 'string', 'max' => 50],
            [['id_seccion_interdiciplinar'], 'exist', 'skipOnError' => true, 'targetClass' => IsmSeccionPlanInterdiciplinar::className(), 'targetAttribute' => ['id_seccion_interdiciplinar' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_seccion_interdiciplinar' => 'Id Seccion Interdiciplinar',
            'nombre_campo' => 'Nombre Campo',
            'activo' => 'Activo',
            'heredado' => 'Heredado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeccionInterdiciplinar()
    {
        return $this->hasOne(IsmSeccionPlanInterdiciplinar::className(), ['id' => 'id_seccion_interdiciplinar']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmRespuestaPlanInterdiciplinars()
    {
        return $this->hasMany(IsmRespuestaPlanInterdiciplinar::className(), ['id_contenido_plan_inter' => 'id']);
    }
}
