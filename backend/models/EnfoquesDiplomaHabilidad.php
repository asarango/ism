<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "enfoques_diploma_habilidad".
 *
 * @property int $id
 * @property string $nombre
 * @property bool $estado
 *
 * @property EnfoquesDiplomaSubHabilidad[] $enfoquesDiplomaSubHabilidads
 */
class EnfoquesDiplomaHabilidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enfoques_diploma_habilidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['estado'], 'boolean'],
            [['nombre'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnfoquesDiplomaSubHabilidads()
    {
        return $this->hasMany(EnfoquesDiplomaSubHabilidad::className(), ['habilidad_id' => 'id']);
    }
}
