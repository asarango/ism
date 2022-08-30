<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_derivacion_institucion_externa".
 *
 * @property int $id
 * @property int $id_dece_derivacion
 * @property int $id_dece_institucion_externa
 *
 * @property DeceDerivacion $deceDerivacion
 * @property DeceInstitucionExterna $deceInstitucionExterna
 */
class DeceDerivacionInstitucionExterna extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_derivacion_institucion_externa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dece_derivacion', 'id_dece_institucion_externa'], 'required'],
            [['id_dece_derivacion', 'id_dece_institucion_externa'], 'default', 'value' => null],
            [['id_dece_derivacion', 'id_dece_institucion_externa'], 'integer'],
            [['id_dece_derivacion'], 'exist', 'skipOnError' => true, 'targetClass' => DeceDerivacion::className(), 'targetAttribute' => ['id_dece_derivacion' => 'id']],
            [['id_dece_institucion_externa'], 'exist', 'skipOnError' => true, 'targetClass' => DeceInstitucionExterna::className(), 'targetAttribute' => ['id_dece_institucion_externa' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dece_derivacion' => 'Id Dece Derivacion',
            'id_dece_institucion_externa' => 'Id Dece Institucion Externa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceDerivacion()
    {
        return $this->hasOne(DeceDerivacion::className(), ['id' => 'id_dece_derivacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceInstitucionExterna()
    {
        return $this->hasOne(DeceInstitucionExterna::className(), ['id' => 'id_dece_institucion_externa']);
    }
}
