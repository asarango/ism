<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "enfoques_diploma_sub_habilidad".
 *
 * @property int $id
 * @property int $habilidad_id
 * @property string $nombre
 * @property bool $estado
 *
 * @property EnfoquesDiplomaSbOpcion[] $enfoquesDiplomaSbOpcions
 * @property EnfoquesDiplomaHabilidad $habilidad
 */
class EnfoquesDiplomaSubHabilidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enfoques_diploma_sub_habilidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['habilidad_id', 'nombre'], 'required'],
            [['habilidad_id'], 'default', 'value' => null],
            [['habilidad_id'], 'integer'],
            [['estado'], 'boolean'],
            [['nombre'], 'string', 'max' => 50],
            [['habilidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => EnfoquesDiplomaHabilidad::className(), 'targetAttribute' => ['habilidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'habilidad_id' => 'Habilidad ID',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnfoquesDiplomaSbOpcions()
    {
        return $this->hasMany(EnfoquesDiplomaSbOpcion::className(), ['sub_habilidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabilidad()
    {
        return $this->hasOne(EnfoquesDiplomaHabilidad::className(), ['id' => 'habilidad_id']);
    }
}
