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
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['accepted_reply'], 'exist', 'skipOnError' => true, 'targetClass' => Replies::className(), 'targetAttribute' => ['accepted_reply' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'city_id' => 'City ID',
            'name' => 'Name',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'files' => 'Files',
            'address' => 'Address',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'budget' => 'Budget',
            'expire' => 'Expire',
            'status' => 'Status',
            'dt_add' => 'Dt Add',
            'accepted_reply' => 'Accepted Reply',
            'executor_id' => 'Executor ID',
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Notifications]].
     *
     * @return \yii\db\ActiveQuery|NotificationsQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notifications::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return \yii\db\ActiveQuery|OpinionsQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinions::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery|RepliesQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoriesQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CitiesQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[AcceptedReply]].
     *
     * @return \yii\db\ActiveQuery|RepliesQuery
     */
    public function getAcceptedReply()
    {
        return $this->hasOne(Replies::className(), ['id' => 'accepted_reply']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::className(), ['id' => 'executor_id']);
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
