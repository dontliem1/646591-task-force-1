<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property int $user_id User
 * @property string|null $about About user
 * @property string|null $address Address
 * @property string|null $bd Birthday
 * @property string|null $categories Chosen specialities
 * @property string|null $phone Tel. number
 * @property string|null $skype Skype login
 * @property string|null $telegram Telegram login
 * @property string|null $notifications Chosen notification types
 * @property int|null $views Profile views
 *
 * @property Users $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'views'], 'integer'],
            [['bd'], 'safe'],
            [['about', 'address', 'categories', 'phone', 'skype', 'telegram', 'notifications'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'about' => 'About',
            'address' => 'Address',
            'bd' => 'Bd',
            'categories' => 'Categories',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'notifications' => 'Notifications',
            'views' => 'Views',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }
}
