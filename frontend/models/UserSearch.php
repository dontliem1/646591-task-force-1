<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use frontend\models\User;

/**
 * UserSearch represents the model behind the search form of `frontend\models\User`.
 */
class UserSearch extends User
{
    public $sort;
    public $categories;
    private $_categories;
    public $isFree;
    public $isOnline;
    public $hasOpinions;
    public $isBookmarked;

    public function getCategories()
    {
        if ($this->_categories === null) {
            $this->_categories = explode(',', $this->categories);
        }
        return $this->_categories;
    }

    public function setCategories($value)
    {
        $this->_categories = explode(',', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isFree', 'isOnline', 'hasOpinions', 'isBookmarked'], 'boolean'],
            ['name', 'string'],
            [['sort', 'categories'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'isFree' => 'Сейчас свободен',
            'isOnline' => 'Сейчас онлайн',
            'hasOpinions' => 'Есть отзывы',
            'isBookmarked' => 'В избранном',
            'name' => 'Поиск по имени',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $usersTable = self::tableName();
        $opinionsTable = Opinion::tableName();
        $profilesTable = Profile::tableName();
        $tasksTable = Task::tableName();
        $bookmarksTable = Bookmark::tableName();

        $query = User::find()
        ->select([$usersTable.'.id', $usersTable.'.dt_add', 'categories', 'about', $usersTable.'.name', 'last_activity_time', 'tasks' => "COUNT($tasksTable.id)", 'opinions' => "COUNT($opinionsTable.id)", 'rating' => "AVG($opinionsTable.rate)"])
        // Исполнителями считаются пользователи, отметившие хотя бы одну категорию у себя в профиле.
        ->where(['IS NOT', 'categories', null])
        ->leftJoin($profilesTable, "$usersTable.id = $profilesTable.user_id")
        ->leftJoin($tasksTable, "$usersTable.id = $tasksTable.executor_id")
        ->leftJoin($opinionsTable, "$usersTable.id = $opinionsTable.executor_id")
        ->groupBy($usersTable.'.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // На странице показывается максимум пять исполнителей. При большем числе записей следует показывать их через пагинацию.
            'pagination' => [
                'pageSize' => 5,
            ],
            // Список исполнителей всегда отсортирован по одному критерию от большего к меньшему.
            'sort' => [
                'attributes' => [
                    'dt_add' => [
                        'default' => SORT_DESC,
                        'label' => false,
                    ],
                    'rating' => [
                        'asc' => ['rating' => SORT_DESC],
                        'desc' => ['rating' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Рейтингу',
                    ],
                    'tasks' => [
                        'asc' => ['tasks' => SORT_DESC],
                        'desc' => ['tasks' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Числу заказов',
                    ],
                    'views' => [
                        'asc' => ['views' => SORT_DESC],
                        'desc' => ['views' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Популярности',
                    ],
                ],
                'defaultOrder' => [
                    'dt_add' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if ($this->name) {
            // Поле «Поиск по имени» сбрасывает все выбранные фильтры и ищет пользователя с нестрогим совпадением по его имени.
            $query->andFilterWhere(['like', $usersTable.'.name', $this->name]);
            $this->categories = $this->isFree = $this->isOnline = $this->hasOpinions = $this->hasOpinions = $this->isBookmarked = null;
        }

        if ($this->categories) {
            foreach ($this->categories as $category) {
                $query->andWhere('MATCH(categories) AGAINST (:category)', [':category' => $category]);
            }
        }
        if ($this->isFree) {
            // добавляет к условию фильтрации показ исполнителей, для которых сейчас нет назначенных активных заданий
            $query->andWhere(['<>', $tasksTable.'.status', Task::STATUS_ACTIVE])->orWhere(['is', $tasksTable.'.id', null]);
        }
        if ($this->isOnline) {
            // добавляет к условию фильтрации показ исполнителей, время последней активности которых было не больше получаса назад
            $query->andFilterCompare($usersTable.'.last_activity_time', date('Y-m-d H:i:s', strtotime('-30 mins')), '>');
        }
        if ($this->hasOpinions) {
            // добавляет к условию фильтрации показ исполнителей с отзывами
            $query->andWhere(['IS NOT', 'opinions.id', null]);
        }
        if ($this->isBookmarked) {
            // добавляет к условию фильтрации показ пользователей, которые были добавлены в избранное
            //TODO подставить текущего пользователя
            $current_user = 1;
            $query->leftJoin($bookmarksTable, "bookmarked_id = $usersTable.id")->andFilterCompare($bookmarksTable.'.user_id', $current_user);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
