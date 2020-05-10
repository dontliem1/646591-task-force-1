<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "replies".
 *
 * @property int $id
 * @property int $task_id Task's id
 * @property int $executor_id Who replied
 * @property string $dt_add Date posted
 * @property int $rate Rating
 * @property int|null $offer Offer
 * @property string|null $description Comment
 * @property int $is_declined Is declined
 *
 * @property Tasks $task
 * @property Users $executor
 * @property Tasks[] $tasks
 */
class Reply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'executor_id', 'dt_add', 'rate'], 'required'],
            [['task_id', 'executor_id', 'rate', 'offer', 'is_declined'], 'integer'],
            [['dt_add'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'executor_id' => 'Executor ID',
            'dt_add' => 'Dt Add',
            'rate' => 'Rate',
            'offer' => 'Offer',
            'description' => 'Description',
            'is_declined' => 'Is Declined',
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
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['accepted_reply' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ReplyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReplyQuery(get_called_class());
    }
}
