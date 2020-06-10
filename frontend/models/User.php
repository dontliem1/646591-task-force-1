<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email Email
 * @property string $name Name and surname
 * @property int $city_id Chosen city
 * @property string $password Password hash
 * @property string $dt_add Date registered
 * @property string|null $last_activity_time Time of last activity
 *
 * @property Bookmark[] $bookmarks
 * @property Bookmark[] $bookmarkedBy
 * @property Message[] $messages
 * @property Message[] $messagesRecieved
 * @property Notification[] $notifications
 * @property Opinion[] $opinions
 * @property Opinion[] $opinionsGot
 * @property Profile $profile
 * @property Reply[] $replies
 * @property Task[] $tasks
 * @property TasksAssigned[] $tasksAssigned
 * @property City $city
 */
class User extends \yii\db\ActiveRecord
{
    const DEFAULT_SORTING = 'dt_add';
    public $rating;
    public $categories;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'city_id', 'password', 'dt_add'], 'required'],
            [['city_id'], 'integer'],
            [['dt_add', 'last_activity_time'], 'safe'],
            [['name', 'password'], 'string', 'max' => 255],
            [['email'], 'email'],
            ['email', 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'city_id' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }

    /**
     * Gets query for [[Bookmarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(Bookmark::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for foreign [[Bookmarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedBy()
    {
        return $this->hasMany(Bookmark::className(), ['bookmarked_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['sender_id' => 'id']);
    }

    /**
     * Gets query for received [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesRecieved()
    {
        return $this->hasMany(Message::className(), ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Notifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinion::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for recieved [[Opinions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinionsGot()
    {
        return $this->hasMany(Opinion::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for assigned [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksAssigned()
    {
        return $this->hasMany(Task::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Gets sorting types
     *
     * @return array
     */
    public static function sortings(): array
    {
        return [
            'rating' => 'Рейтингу',
            'tasks' => 'Числу заказов',
            'views' => 'Популярности',
        ];
    }
}
