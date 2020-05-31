<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "bookmarks".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $bookmarked_id Избранный пользователь
 *
 * @property User $user
 * @property User $bookmarked
 */
class Bookmark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookmarks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bookmarked_id'], 'required'],
            [['user_id', 'bookmarked_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['bookmarked_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['bookmarked_id' => 'id']],
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
            'bookmarked_id' => 'Bookmarked ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Bookmarked]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getBookmarked()
    {
        return $this->hasOne(User::className(), ['id' => 'bookmarked_id']);
    }

    /**
     * {@inheritdoc}
     * @return BookmarksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BookmarksQuery(get_called_class());
    }
}
