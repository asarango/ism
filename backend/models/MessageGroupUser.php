<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message_group_user".
 *
 * @property int $id
 * @property int $message_group_id
 * @property string $usuario
 *
 * @property MessageGroup $messageGroup
 * @property Usuario $usuario0
 */
class MessageGroupUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_group_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_group_id', 'usuario'], 'required'],
            [['message_group_id'], 'default', 'value' => null],
            [['message_group_id'], 'integer'],
            [['usuario'], 'string', 'max' => 200],
            [['message_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => MessageGroup::className(), 'targetAttribute' => ['message_group_id' => 'id']],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_group_id' => 'Message Group ID',
            'usuario' => 'Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageGroup()
    {
        return $this->hasOne(MessageGroup::className(), ['id' => 'message_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'usuario']);
    }
}
