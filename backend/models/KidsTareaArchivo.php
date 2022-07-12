<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_tarea_archivo".
 *
 * @property int $id
 * @property int $tarea_id
 * @property string $archivo
 *
 * @property KidsDestrezaTarea $tarea
 */
class KidsTareaArchivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_tarea_archivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tarea_id', 'archivo'], 'required'],
            [['tarea_id'], 'default', 'value' => null],
            [['tarea_id'], 'integer'],
            [['archivo'], 'string', 'max' => 255],
            [['tarea_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDestrezaTarea::className(), 'targetAttribute' => ['tarea_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tarea_id' => 'Tarea ID',
            'archivo' => 'Archivo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarea()
    {
        return $this->hasOne(KidsDestrezaTarea::className(), ['id' => 'tarea_id']);
    }
}
