<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "opinions".
 *
 * @property int $id
 * @property string $dt_add Date of the opinion
 * @property int $had_problems Was problems or not
 * @property string|null $description Customer's comment
 * @property int $rate Rating
 * @property int $task_id Task's id
 * @property int $customer_id Customer's id
 * @property int $executor_id Executor's id
 *
 * @property Tasks $task
 * @property Users $customer
 * @property Users $executor
 */
class Opinion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opinions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add', 'had_problems', 'rate', 'task_id', 'customer_id', 'executor_id'], 'required'],
            [['dt_add'], 'safe'],
            [['had_problems', 'rate', 'task_id', 'customer_id', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
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
            'dt_add' => 'Dt Add',
            'had_problems' => 'Had Problems',
            'description' => 'Description',
            'rate' => 'Rate',
            'task_id' => 'Task ID',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
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
     * {@inheritdoc}
     * @return OpinionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpinionQuery(get_called_class());
    }
}
