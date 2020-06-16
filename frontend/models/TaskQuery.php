<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Task]].
 *
 * @see Task
 */
class TaskQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Task[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Task|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    /**
     * Filters query by selected categories
     *
     * @param  array $categories selected categories
     * @return UserQuery
     */
    public function filterByCategories(array $categories)
    {
        $categoriesQuery = ['or'];
        foreach ($categories as $category) {
            $categoriesQuery[] = [Category::tableName().'.icon'=>$category];
        }
        return $this->joinWith('category')->andWhere($categoriesQuery);
    }
    
    /**
     * Selects only task without replies
     * Добавляет к условию фильтрации показ заданий только без откликов исполнителей
     * 
     * @return UserQuery
     */
    public function hasNoReplies()
    {
        return $this->joinWith('replies')->andWhere(['IS', Reply::tableName().'.id', null]);
    }
    
    /**
     * Selects only task without address
     * Добавляет к условию фильтрации показ заданий без географической привязки
     * 
     * @return UserQuery
     */
    public function isRemote()
    {
        $this->andWhere(['is', 'address', null]);
    }
    
    /**
     * Filters query by selected period
     * Выпадающий список «Период» добавляет к условию фильтрации диапазон времени, когда было создано задание
     * 
     * @param  string $period selected period
     * @return UserQuery
     */
    public function filterByPeriod(string $period)
    {
        return $this->andFilterCompare(Task::tableName().'.dt_add', date('Y-m-d', strtotime('-1 day')), '>');
    }

}
