<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_intervencion_area_compromiso".
 *
 * @property int $id
 * @property int $id_dece_intervencion
 * @property int $id_dece_areas_intervenir
 *
 * @property DeceAreasIntervenir $deceAreasIntervenir
 * @property DeceIntervencion $deceIntervencion
 */
class DeceIntervencionAreaCompromiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_intervencion_area_compromiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dece_intervencion', 'id_dece_areas_intervenir'], 'required'],
            [['id_dece_intervencion', 'id_dece_areas_intervenir'], 'default', 'value' => null],
            [['id_dece_intervencion', 'id_dece_areas_intervenir'], 'integer'],
            [['id_dece_areas_intervenir'], 'exist', 'skipOnError' => true, 'targetClass' => DeceAreasIntervenir::className(), 'targetAttribute' => ['id_dece_areas_intervenir' => 'id']],
            [['id_dece_intervencion'], 'exist', 'skipOnError' => true, 'targetClass' => DeceIntervencion::className(), 'targetAttribute' => ['id_dece_intervencion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dece_intervencion' => 'Id Dece Intervencion',
            'id_dece_areas_intervenir' => 'Id Dece Areas Intervenir',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceAreasIntervenir()
    {
        return $this->hasOne(DeceAreasIntervenir::className(), ['id' => 'id_dece_areas_intervenir']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceIntervencion()
    {
        return $this->hasOne(DeceIntervencion::className(), ['id' => 'id_dece_intervencion']);
    }
}
