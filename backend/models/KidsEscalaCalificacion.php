<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_escala_calificacion".
 *
 * @property int $id
 * @property string $escala
 * @property string $significado
 * @property string $caracteristica
 * @property int $equivalencia
 * @property string $icono_font_awesome
 *
 * @property KidsCalificaTarea[] $kidsCalificaTareas
 */
class KidsEscalaCalificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_escala_calificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['escala', 'significado', 'caracteristica', 'equivalencia', 'icono_font_awesome'], 'required'],
            [['caracteristica'], 'string'],
            [['equivalencia'], 'default', 'value' => null],
            [['equivalencia'], 'integer'],
            [['escala'], 'string', 'max' => 5],
            [['significado'], 'string', 'max' => 30],
            [['icono_font_awesome'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'escala' => 'Escala',
            'significado' => 'Significado',
            'caracteristica' => 'Caracteristica',
            'equivalencia' => 'Equivalencia',
            'icono_font_awesome' => 'Icono Font Awesome',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidsCalificaTareas()
    {
        return $this->hasMany(KidsCalificaTarea::className(), ['escala_id' => 'id']);
    }
}
