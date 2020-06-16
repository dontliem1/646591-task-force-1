<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{    
    /**
     * Select only executors
     * Исполнителями считаются пользователи, отметившие хотя бы одну категорию у себя в профиле.
     *
     * @return UserQuery
     */
    public function executors()
    {
        return $this->joinWith(['profile', 'opinionsGot'])->where(['IS NOT', 'categories', null]);
    }

    /**
     * Selects aliases for sorting
     *
     * @return UserQuery
     */
    public function withSortings()
    {
        return $this->select([User::tableName().'.*', 'rating' => 'AVG([[rate]])', 'tasksAssignedCount' => 'COUNT('.Task::tableName().'.id)'])->joinWith(['opinionsGot','tasksAssigned'])->groupBy(User::tableName().'.id');
    }
    
    /**
     * Filters query by selected categories
     *
     * @param  array $category selected categories
     * @return UserQuery
     */
    public function filterByCategories(array $categories)
    {
        $categoriesQuery = ['or'];
        foreach ($categories as $category) {
            $categoriesQuery[] = "MATCH(categories) AGAINST ('$category')";
        }
        return $this->andWhere($categoriesQuery);
    }
    
    /**
     * Selects only bookmarked users
     * Добавляет к условию фильтрации показ пользователей, которые были добавлены в избранное
     * 
     * @param  int $userId current user's id
     * @return UserQuery
     */
    public function isBookmarkedBy(int $userId)
    {
        return $this->joinWith('bookmarks')->andFilterCompare(Bookmark::tableName().'.user_id', $userId);
    }
    
    /**
     * Selects only free users
     * Добавляет к условию фильтрации показ исполнителей, для которых сейчас нет назначенных активных заданий
     * 
     * @return UserQuery
     */
    public function isFree()
    {
        return $this->andWhere(['<>', 'status', Task::STATUS_ACTIVE])->orWhere(['is', Task::tableName().'.id', null]);
    }
    
    /**
     * Selects only online users
     * Добавляет к условию фильтрации показ исполнителей, время последней активности которых было не больше получаса назад
     * 
     * @return UserQuery
     */
    public function isOnline()
    {
        return $this->andFilterCompare('last_activity_time', date('Y-m-d H:i:s', strtotime('-30 mins')), '>');
    }
    
    /**
     * Selects only users with feedback
     * Добавляет к условию фильтрации показ исполнителей с отзывами
     * 
     * @return UserQuery
     */
    public function gotOpinions()
    {
        return $this->andWhere(['IS NOT', Opinion::tableName().'.id', null]);
    }

    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
