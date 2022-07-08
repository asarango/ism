<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "enfoques_diploma_sb_opcion".
 *
 * @property int $id
 * @property int $sub_habilidad_id
 * @property string $nombre
 * @property bool $estado
 *
 * @property EnfoquesDiplomaSubHabilidad $subHabilidad
 */
class EnfoquesDiplomaSbOpcion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enfoques_diploma_sb_opcion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_habilidad_id', 'nombre'], 'required'],
            [['sub_habilidad_id'], 'default', 'value' => null],
            [['sub_habilidad_id'], 'integer'],
            [['nombre'], 'string'],
            [['estado'], 'boolean'],
            [['sub_habilidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => EnfoquesDiplomaSubHabilidad::className(), 'targetAttribute' => ['sub_habilidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sub_habilidad_id' => 'Sub Habilidad ID',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubHabilidad()
    {
        return $this->hasOne(EnfoquesDiplomaSubHabilidad::className(), ['id' => 'sub_habilidad_id']);
    }
}
