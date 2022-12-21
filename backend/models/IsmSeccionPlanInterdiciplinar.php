<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_seccion_plan_interdiciplinar".
 *
 * @property int $id
 * @property int $num_seccion
 * @property string $nombre_seccion
 * @property bool $activo
 *
 * @property IsmContenidoPlanInterdiciplinar[] $ismContenidoPlanInterdiciplinars
 */
class IsmSeccionPlanInterdiciplinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_seccion_plan_interdiciplinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num_seccion', 'nombre_seccion', 'activo'], 'required'],
            [['num_seccion'], 'default', 'value' => null],
            [['num_seccion'], 'integer'],
            [['activo'], 'boolean'],
            [['nombre_seccion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'num_seccion' => 'Num Seccion',
            'nombre_seccion' => 'Nombre Seccion',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmContenidoPlanInterdiciplinars()
    {
        return $this->hasMany(IsmContenidoPlanInterdiciplinar::className(), ['id_seccion_interdiciplinar' => 'id']);
    }
}
