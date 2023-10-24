<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "visitas_aulicas_observaciones_docente".
 *
 * @property int $id
 * @property int $visita_id
 * @property int $visita_catalogo_id
 * @property bool $se_hace
 * @property string $comentarios
 *
 * @property VisitaAulica $visita
 * @property VisitasAulicasCatalogo $visitaCatalogo
 */
class VisitasAulicasObservacionesDocente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitas_aulicas_observaciones_docente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visita_id', 'visita_catalogo_id'], 'required'],
            [['visita_id', 'visita_catalogo_id'], 'default', 'value' => null],
            [['visita_id', 'visita_catalogo_id'], 'integer'],
            [['se_hace'], 'boolean'],
            [['comentarios'], 'string'],
            [['visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitaAulica::className(), 'targetAttribute' => ['visita_id' => 'id']],
            [['visita_catalogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitasAulicasCatalogo::className(), 'targetAttribute' => ['visita_catalogo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visita_id' => 'Visita ID',
            'visita_catalogo_id' => 'Visita Catalogo ID',
            'se_hace' => 'Se Hace',
            'comentarios' => 'Comentarios',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisita()
    {
        return $this->hasOne(VisitaAulica::className(), ['id' => 'visita_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitaCatalogo()
    {
        return $this->hasOne(VisitasAulicasCatalogo::className(), ['id' => 'visita_catalogo_id']);
    }
}
