<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "visitas_aulicas_catalogo".
 *
 * @property int $id
 * @property string $tipo
 * @property string $opcion
 * @property bool $es_activo
 *
 * @property VisitasAulicasObservacionesDocente[] $visitasAulicasObservacionesDocentes
 */
class VisitasAulicasCatalogo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitas_aulicas_catalogo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'opcion'], 'required'],
            [['es_activo'], 'boolean'],
            [['tipo'], 'string', 'max' => 30],
            [['opcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo' => 'Tipo',
            'opcion' => 'Opcion',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitasAulicasObservacionesDocentes()
    {
        return $this->hasMany(VisitasAulicasObservacionesDocente::className(), ['visita_catalogo_id' => 'id']);
    }
}
