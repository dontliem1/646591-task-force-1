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
 * @property Task[] $tasksAssigned
 * @property City $city
 */
class User extends \yii\db\ActiveRecord
{
    private $_tasksCount;
    private $_tasksAssignedCount;
    private $_opinionsGotCount;
    private $_rating;
    
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
        return $this->hasMany(Bookmark::className(), ['bookmarked_id' => 'id']);
    }

    /**
     * Gets query for foreign [[Bookmarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedBy()
    {
        return $this->hasMany(Bookmark::className(), ['user_id' => 'id']);
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
     * Sets virtual property of received opinions count
     *
     * @param  mixed $count
     * @return \yii\db\ActiveQuery
     */
    public function setOpinionsGotCount($count)
    {
        $this->_opinionsGotCount = (int) $count;
    }
    
    /**
     * Gets query for counting received opinions
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinionsGotCount()
    {
        if ($this->_opinionsGotCount === null) {
            $this->setOpinionsGotCount($this->getOpinionsGot()->count());
        }
        return $this->_opinionsGotCount;
    }
    
    /**
     * Sets virtual property of rating
     *
     * @param  mixed $count
     * @return \yii\db\ActiveQuery
     */
    public function setRating($count)
    {
        $this->_rating = (int) $count;
    }
    
    /**
     * Gets query for counting received opinions
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRating()
    {
        if ($this->_rating === null) {
            $this->setRating($this->getOpinionsGot()->average('rate'));
        }
        return $this->_rating;
    }

    /**
     * Gets query for [[last_activity_time]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLastActivityTime()
    {
        return $this->last_activity_time;
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
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->profile->categories;
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
     * Sets virtual property of tasks count
     *
     * @param  mixed $count
     * @return \yii\db\ActiveQuery
     */
    public function setTasksCount($count)
    {
        $this->_tasksCount = (int) $count;
    }
    
    /**
     * Gets query for counting Tasks
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksCount()
    {
        if ($this->_tasksCount === null) {
            $this->setTasksCount($this->getTasks()->count());
        }
        return $this->_tasksCount;
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
     * Sets virtual property of assigned tasks count
     *
     * @param  mixed $count
     * @return \yii\db\ActiveQuery
     */
    public function setTasksAssignedCount($count)
    {
        $this->_tasksAssignedCount = (int) $count;
    }
    
    /**
     * Gets query for counting assigned Tasks
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksAssignedCount()
    {
        if ($this->_tasksAssignedCount === null) {
            $this->setTasksAssignedCount($this->getTasksAssigned()->count());
        }
        return $this->_tasksAssignedCount;
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
     * Gets query for [[dt_add]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDtAdd()
    {
        return $this->dt_add;
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
