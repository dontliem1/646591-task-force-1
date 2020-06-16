<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use frontend\models\User;

/**
 * UserSearch represents the model behind the search form of `frontend\models\User`.
 */
class UserSearch extends User
{
    public $dtAdd;
    public $sort;
    public $categories;
    public $isFree;
    public $isOnline;
    public $gotOpinions;
    public $isBookmarked;

    /**
     * {@inheritdoc}
     * TODO сделать валидацию categories
     */
    public function rules()
    {
        return [
            [['isFree', 'isOnline', 'gotOpinions', 'isBookmarked'], 'boolean'],
            ['name', 'string'],
            [['sort', 'categories', 'dtAdd'], 'safe'],
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
            'gotOpinions' => 'Есть отзывы',
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
        $usersTable = User::tableName();

        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query->executors()->withSortings(),
            // На странице показывается максимум пять исполнителей. При большем числе записей следует показывать их через пагинацию.
            'pagination' => [
                'pageSize' => 5,
            ],
            // Список исполнителей всегда отсортирован по одному критерию от большего к меньшему.
            'sort' => [
                'attributes' => [
                    'dtAdd' => [
                        'asc' => [$usersTable.'.dt_add' => SORT_DESC],
                        'desc' => [$usersTable.'.dt_add' => SORT_DESC],
                        'label' => false,
                    ],
                    'rating' => [
                        'asc' => ['rating' => SORT_DESC],
                        'label' => 'Рейтингу',
                    ],
                    'tasksAssignedCount' => [
                        'asc' => ['tasksAssignedCount' => SORT_DESC],
                        'label' => 'Числу заказов',
                    ],
                    'views' => [
                        'asc' => ['views' => SORT_DESC],
                        'label' => 'Популярности',
                    ],
                ],
                'defaultOrder' => [
                    'dtAdd' => SORT_DESC,
                ],
            ],
        ]);

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'name':
                    if (!empty($value)) {
                        // Поле «Поиск по имени» сбрасывает все выбранные фильтры и ищет пользователя с нестрогим совпадением по его имени.
                        $query->andWhere(['like', $usersTable.'.name', $value]);
                        $params = [$key => $value];
                    }
                    break;
                case 'categories':
                    $query->filterByCategories($value);
                    break;
                case 'isFree':
                    if ($value) {
                        $query->isFree();
                    }
                    break;
                case 'isOnline':
                    if ($value) {
                        $query->isOnline();
                    }
                    break;
                case 'gotOpinions':
                    if ($value) {
                        $query->gotOpinions();
                    }
                    break;
                case 'isBookmarked':
                    if ($value) {
                        //TODO подставить текущего пользователя
                        $query->isBookmarkedBy(1);
                    }
                    break;
            }
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
