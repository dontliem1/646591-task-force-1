<?php

namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

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
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_CUSTOMER = 'customer';
    const ROLE_EXECUTOR = 'executor';

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
     * Gets query for bookmarked by current user status.
     * TODO Заменить 1 на текущего пользователя]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedByCurrentUser()
    {
        return $this->hasOne(Bookmark::className(), ['bookmarked_id' => 'id'])->andOnCondition(['user_id' => 1]);
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
     * Gets chosen [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->profile->categories;
    }

    /**
     * Gets user's role.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return empty($this->categories) ? self::ROLE_CUSTOMER : self::ROLE_EXECUTOR;
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
     * Sets virtual property of tasks count
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityId()
    {
        return $this->city_id;
    }
    
    /**
     * Sets virtual property of tasks count
     *
     * @param  int $id
     * @return \yii\db\ActiveQuery
     */
    public function setCityId(int $id)
    {
        $this->city_id = $id;
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

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['email' => $username]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
}
