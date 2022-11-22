<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message_group".
 *
 * @property int $id
 * @property int $scholaris_periodo_id
 * @property int $source_id
 * @property string $source_table
 * @property string $nombre
 * @property string $tipo
 * @property bool $estado
 *
 * @property ScholarisPeriodo $scholarisPeriodo
 * @property MessageGroupUser[] $messageGroupUsers
 */
class MessageGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scholaris_periodo_id', 'nombre', 'tipo'], 'required'],
            [['scholaris_periodo_id', 'source_id'], 'default', 'value' => null],
            [['scholaris_periodo_id', 'source_id'], 'integer'],
            [['estado'], 'boolean'],
            [['source_table'], 'string', 'max' => 50],
            [['nombre'], 'string', 'max' => 60],
            [['tipo'], 'string', 'max' => 20],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
            'source_id' => 'Source ID',
            'source_table' => 'Source Table',
            'nombre' => 'Nombre',
            'tipo' => 'Tipo',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageGroupUsers()
    {
        return $this->hasMany(MessageGroupUser::className(), ['message_group_id' => 'id']);
    }
}
