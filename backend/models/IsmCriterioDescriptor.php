<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_criterio_descriptor".
 *
 * @property int $id
 * @property int $ism_criterio_literal_area_id
 * @property int $op_course_template_id
 * @property string $numeral
 * @property int $orden
 * @property string $nombre_espanol
 * @property string $nombre_ingles
 * @property string $nombre_frances
 * @property bool $es_activo
 *
 * @property IsmCriterioLiteralArea $ismCriterioLiteralArea
 * @property OpCourseTemplate $opCourseTemplate
 */
class IsmCriterioDescriptor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_criterio_descriptor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ism_criterio_literal_area_id', 'op_course_template_id', 'numeral', 'orden', 'nombre_espanol'], 'required'],
            [['ism_criterio_literal_area_id', 'op_course_template_id', 'orden'], 'default', 'value' => null],
            [['ism_criterio_literal_area_id', 'op_course_template_id', 'orden'], 'integer'],
            [['es_activo'], 'boolean'],
            [['numeral'], 'string', 'max' => 5],
            [['nombre_espanol', 'nombre_ingles', 'nombre_frances'], 'string', 'max' => 500],
            [['ism_criterio_literal_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmCriterioLiteralArea::className(), 'targetAttribute' => ['ism_criterio_literal_area_id' => 'id']],
            [['op_course_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['op_course_template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ism_criterio_literal_area_id' => 'Ism Criterio Literal Area ID',
            'op_course_template_id' => 'Op Course Template ID',
            'numeral' => 'Numeral',
            'orden' => 'Orden',
            'nombre_espanol' => 'Nombre Espanol',
            'nombre_ingles' => 'Nombre Ingles',
            'nombre_frances' => 'Nombre Frances',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioLiteralArea()
    {
        return $this->hasOne(IsmCriterioLiteralArea::className(), ['id' => 'ism_criterio_literal_area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'op_course_template_id']);
    }
}
