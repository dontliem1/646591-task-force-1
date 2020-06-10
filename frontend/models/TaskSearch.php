<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use frontend\models\Task;

/**
 * TaskSearch represents the model behind the search form of `frontend\models\Task`.
 */
class TaskSearch extends Task
{
    public $categories;
    private $_categories;
    public $hasNoReplies;
    public $isRemote;
    public $period;

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
     * Gets periods for filtering
     *
     * @return array
     */
    public static function periods(): array
    {
        return [
            'all'=>'За всё время',
            'day'=>'За день',
            'week'=>'За неделю',
            'month'=>'За месяц',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hasNoReplies', 'isRemote'], 'boolean'],
            ['name', 'string'],
            [['period', 'categories'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'hasNoReplies' => 'Без откликов',
            'isRemote' => 'Удаленная работа',
            'period' => 'Период',
            'name' => 'Поиск по названию',
        ];
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
        $taskTable = self::tableName();

        //TODO Показываются только задания без привязки к адресу, а также из города пользователя, либо из города, выбранного пользователем в текущей сессии.
        $query = Task::find()->where(['status' => Task::STATUS_NEW]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // На странице показывается максимум пять заданий. При большем числе записей следует показывать их через пагинацию.
            'pagination' => [
                'pageSize' => 2,
            ],
            'sort' => [
                'defaultOrder' => [
                    'dt_add' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if ($this->categories) {
            $categoriesTable = Category::tableName();
            $categoriesQuery = ['or'];
            foreach ($this->categories as $category) {
                $categoriesQuery[] = [$categoriesTable.'.icon'=>$category];
            }
            $query->leftJoin($categoriesTable, "$taskTable.category_id = $categoriesTable.id")->andWhere($categoriesQuery);
        }
        if ($this->hasNoReplies) {
            // добавляет к условию фильтрации показ заданий только без откликов исполнителей
            $repliesTable = Reply::tableName();
            $query->leftJoin($repliesTable, "$repliesTable.task_id = $taskTable.id")->andWhere(['IS', $repliesTable.'.id', null])->groupBy($taskTable.'.id');
        }
        if ($this->isRemote) {
            // добавляет к условию фильтрации показ заданий без географической привязки
            $query->andWhere(['is', 'address', null]);
        }
        if ($this->period) {
            // Выпадающий список «Период» добавляет к условию фильтрации диапазон времени, когда было создано задание
            switch ($this->period) {
                case 'day':
                    $query->andFilterCompare($taskTable.'.dt_add', date('Y-m-d', strtotime('-1 day')), '>');
                    break;
                case 'week':
                    $query->andFilterCompare($taskTable.'.dt_add', date('Y-m-d', strtotime('-1 week')), '>');
                    break;
                case 'month':
                    $query->andFilterCompare($taskTable.'.dt_add', date('Y-m-d', strtotime('-1 month')), '>');
                    break;
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Поле «Поиск по названию» добавляет к условию фильтрации нестрогий поиск по совпадению в названии задания.
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }
}
