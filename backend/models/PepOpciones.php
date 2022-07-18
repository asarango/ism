<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pep_opciones".
 *
 * @property int $id
 * @property string $tipo
 * @property string $categoria_principal_es
 * @property string $categoria_secundaria_es
 * @property string $contenido_es
 * @property string $categoria_principal_en
 * @property string $categoria_secundaria_en
 * @property string $contenido_en
 * @property string $categoria_principal_fr
 * @property string $categoria_secundaria_fr
 * @property string $contenido_fr
 * @property string $campo_de
 * @property bool $es_activo
 *
 * @property PepPlanificacionXUnidad[] $pepPlanificacionXUnidads
 */
class PepOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pep_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'campo_de'], 'required'],
            [['categoria_principal_es', 'categoria_secundaria_es', 'contenido_es', 'categoria_principal_en', 'categoria_secundaria_en', 'contenido_en', 'categoria_principal_fr', 'categoria_secundaria_fr', 'contenido_fr'], 'string'],
            [['es_activo'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
            [['campo_de'], 'string', 'max' => 20],
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
            'categoria_principal_es' => 'Categoria Principal Es',
            'categoria_secundaria_es' => 'Categoria Secundaria Es',
            'contenido_es' => 'Contenido Es',
            'categoria_principal_en' => 'Categoria Principal En',
            'categoria_secundaria_en' => 'Categoria Secundaria En',
            'contenido_en' => 'Contenido En',
            'categoria_principal_fr' => 'Categoria Principal Fr',
            'categoria_secundaria_fr' => 'Categoria Secundaria Fr',
            'contenido_fr' => 'Contenido Fr',
            'campo_de' => 'Campo De',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPepPlanificacionXUnidads()
    {
        return $this->hasMany(PepPlanificacionXUnidad::className(), ['tema_transdisciplinar_id' => 'id']);
    }
}
