<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_areas_intervenir".
 *
 * @property int $id
 * @property string $code
 * @property string $nombre
 *
 * @property DeceIntervencionAreaCompromiso[] $deceIntervencionAreaCompromisos
 */
class DeceAreasIntervenir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_areas_intervenir';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'nombre'], 'required'],
            [['code'], 'string', 'max' => 3],
            [['nombre'], 'string', 'max' => 50],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceIntervencionAreaCompromisos()
    {
        return $this->hasMany(DeceIntervencionAreaCompromiso::className(), ['id_dece_areas_intervenir' => 'id']);
    }
}
