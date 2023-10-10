<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_studiantes_cv".
 *
 * @property int $estudiante_id
 * @property int $inscription_id
 * @property string $seccion
 * @property string $curso
 * @property string $paralelo
 * @property string $estudiante
 * @property string $inscription_state
 */
class ViewStudiantesCv extends \yii\db\ActiveRecord
{

    public $estudinate_id; // Columna virtual

    public static function primaryKey()
    {
        return ['estudiante_id'];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_studiantes_cv';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estudiante_id', 'inscription_id'], 'default', 'value' => null],
            [['estudiante_id', 'inscription_id'], 'integer'],
            [['seccion', 'paralelo', 'estudiante', 'inscription_state'], 'string'],
            [['curso'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'estudiante_id' => 'Estudiante ID',
            'inscription_id' => 'Inscription ID',
            'seccion' => 'Seccion',
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'estudiante' => 'Estudiante',
            'inscription_state' => 'Inscription State',
        ];
    }
}
