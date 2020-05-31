<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * TaskFilterForm is the model behind the tasks filter form.
 */
class TaskFilterForm extends Model
{
    public $categories;
    private $_categories;
    public $hasNoReplies;
    public $isRemote;
    public $period;
    public $name;

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
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }
}
