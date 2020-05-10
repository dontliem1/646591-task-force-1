<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $time_sent Time of sending
 * @property int $task_id Task's id
 * @property int $sender_id Sender
 * @property int $recipient_id Recipient
 * @property string $message Message's body
 *
 * @property Tasks $task
 * @property Users $sender
 * @property Users $recipient
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time_sent'], 'safe'],
            [['task_id', 'sender_id', 'recipient_id', 'message'], 'required'],
            [['task_id', 'sender_id', 'recipient_id'], 'integer'],
            [['message'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time_sent' => 'Time Sent',
            'task_id' => 'Task ID',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
            'message' => 'Message',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Recipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }

    /**
     * {@inheritdoc}
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }
}
