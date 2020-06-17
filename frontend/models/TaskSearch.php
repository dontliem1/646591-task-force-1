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
    public $hasNoReplies;
    public $isRemote;
    public $period;

    /**
     * Gets periods for filtering
     *
     * @return array
     */
    public static function periods(): array
    {
        return [
            ''=>'За всё время',
            'day'=>'За день',
            'week'=>'За неделю',
            'month'=>'За месяц',
        ];
    }

    /**
     * {@inheritdoc}
     * TODO сделать валидацию categories и period
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
        $taskTable = Task::tableName();

        //TODO Показываются только задания без привязки к адресу, а также из города пользователя, либо из города, выбранного пользователем в текущей сессии.
        $query = Task::find()->where(['status' => Task::STATUS_NEW]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // На странице показывается максимум пять заданий. При большем числе записей следует показывать их через пагинацию.
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'attributes' => [
                    'dtAdd' => [
                        'asc' => [$taskTable.'.dt_add' => SORT_DESC],
                        'desc' => [$taskTable.'.dt_add' => SORT_DESC],
                        'label' => false,
                    ]
                ],
                'defaultOrder' => [
                    'dtAdd' => SORT_DESC,
                ]
            ],
        ]);

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'categories':
                    $query->filterByCategories($value);
                    break;
                case 'hasNoReplies':
                    if ($value) {
                        $query->hasNoReplies();
                    }
                    break;
                case 'isRemote':
                    if ($value) {
                        $query->isRemote();
                    }
                    break;
                case 'period':
                    if ($value) {
                        $query->filterByPeriod($value);
                    }
                    break;
                case 'name':
                    $query->andFilterWhere(['like', $taskTable.'.name', $value]);
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

    /**
     * Creates data provider instance for landing
     * Здесь следует разместить карточки четырёх самых свежих заданий, с сортировкой по дате в порядке убывания. 
     *
     * @return ActiveDataProvider
     */
    public function landing()
    {
        $taskTable = Task::tableName();
        
        $query = Task::find()->where(['status' => Task::STATUS_NEW])->limit(4);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'dtAdd' => [
                        'asc' => [$taskTable.'.dt_add' => SORT_DESC],
                        'desc' => [$taskTable.'.dt_add' => SORT_DESC],
                        'label' => false,
                    ]
                ],
                'defaultOrder' => [
                    'dtAdd' => SORT_DESC,
                ]
            ],
        ]);

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
