<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_criterio_literal".
 *
 * @property int $id
 * @property int $criterio_id
 * @property string $nombre_espanol
 * @property string $nombre_ingles
 * @property string $nombre_frances
 * @property bool $es_activo
 *
 * @property IsmCriterioDescriptorArea[] $ismCriterioDescriptorAreas
 * @property IsmCriterio $criterio
 * @property IsmCriterioLiteralArea[] $ismCriterioLiteralAreas
 */
class IsmCriterioLiteral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_criterio_literal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['criterio_id', 'nombre_espanol'], 'required'],
            [['criterio_id'], 'default', 'value' => null],
            [['criterio_id'], 'integer'],
            [['es_activo'], 'boolean'],
            [['nombre_espanol', 'nombre_ingles', 'nombre_frances'], 'string', 'max' => 100],
            [['criterio_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmCriterio::className(), 'targetAttribute' => ['criterio_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'criterio_id' => 'Criterio ID',
            'nombre_espanol' => 'Nombre Espanol',
            'nombre_ingles' => 'Nombre Ingles',
            'nombre_frances' => 'Nombre Frances',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioDescriptorAreas()
    {
        return $this->hasMany(IsmCriterioDescriptorArea::className(), ['id_literal_criterio' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterio()
    {
        return $this->hasOne(IsmCriterio::className(), ['id' => 'criterio_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioLiteralAreas()
    {
        return $this->hasMany(IsmCriterioLiteralArea::className(), ['ism_criterio_literal_id' => 'id']);
    }
}
