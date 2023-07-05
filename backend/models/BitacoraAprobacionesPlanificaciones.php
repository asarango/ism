<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "bitacora_aprobaciones_planificaciones".
 *
 * @property int $id
 * @property string $tipo_documento
 * @property string $link_pdf
 * @property string $fecha
 * @property string $estado
 * @property string $enviado_a
 * @property string $creado_por
 * @property string $fecha_creado
 * @property string $observaciones
 */
class BitacoraAprobacionesPlanificaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bitacora_aprobaciones_planificaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_documento', 'link_pdf', 'fecha', 'estado', 'creado_por', 'fecha_creado'], 'required'],
            [['fecha', 'fecha_creado'], 'safe'],
            [['observaciones'], 'string'],
            [['tipo_documento'], 'string', 'max' => 50],
            [['link_pdf', 'enviado_a', 'creado_por'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_documento' => 'Tipo Documento',
            'link_pdf' => 'Link Pdf',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
            'enviado_a' => 'Enviado A',
            'creado_por' => 'Creado Por',
            'fecha_creado' => 'Fecha Creado',
            'observaciones' => 'Observaciones',
        ];
    }
}
