<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_motivos".
 *
 * @property int $id
 * @property string $motivo
 */
class DeceMotivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_motivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['motivo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'motivo' => 'Motivo',
        ];
    }
}
