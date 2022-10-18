<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_institucion_externa".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property DeceDerivacionInstitucionExterna[] $deceDerivacionInstitucionExternas
 */
class DeceInstitucionExterna extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_institucion_externa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 10],
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
            'code' => 'Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceDerivacionInstitucionExternas()
    {
        return $this->hasMany(DeceDerivacionInstitucionExterna::className(), ['id_dece_institucion_externa' => 'id']);
    }
}
