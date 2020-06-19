<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $customer_id Author
 * @property int $city_id Chosen city
 * @property string $name Title
 * @property string $description Description
 * @property int $category_id Chosen category
 * @property string|null $files Paths to files
 * @property string|null $address Address
 * @property float $lat Latitude
 * @property float $lng Longitude
 * @property int|null $budget Budget
 * @property string|null $expire Deadline
 * @property string $status Status
 * @property string $dt_add Time created
 * @property int|null $accepted_reply Accepted reply
 * @property int|null $executor_id Chosen executor
 *
 * @property Messages[] $messages
 * @property Notifications[] $notifications
 * @property Opinions[] $opinions
 * @property Replies[] $replies
 * @property Categories $category
 * @property Cities $city
 * @property Users $customer
 * @property Replies $acceptedReply
 * @property Users $executor
 */
class Task extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'city_id', 'name', 'description', 'category_id', 'lat', 'lng', 'dt_add'], 'required'],
            [['customer_id', 'city_id', 'category_id', 'budget', 'accepted_reply', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['lat', 'lng'], 'number'],
            [['expire', 'dt_add'], 'safe'],
            [['name', 'files', 'address', 'status'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['accepted_reply'], 'exist', 'skipOnError' => true, 'targetClass' => Reply::className(), 'targetAttribute' => ['accepted_reply' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[Message]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Notification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Opinion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinion::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Sets [[category_id]]
     *
     * @param  int $id
     */
    public function setCategoryId(int $id)
    {
        $this->category_id = $id;
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
    
    /**
     * Gets virtual property of city id
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityId()
    {
        return $this->city_id;
    }
    
    /**
     * Sets virtual property of city id
     *
     * @param  int $id
     */
    public function setCityId(int $id)
    {
        $this->city_id = $id;
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
     * Gets query for [[AcceptedReply]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptedReply()
    {
        return $this->hasOne(Reply::className(), ['id' => 'accepted_reply']);
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
     * Gets query for [[dt_add]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDtAdd()
    {
        return $this->dt_add;
    }

    /**
     * Sets [[customer_id]].
     *
     * @param int $id id of a current user
     */
    public function setCustomerId(int $id)
    {
        return $this->customer_id = $id;
    } 

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }
}
