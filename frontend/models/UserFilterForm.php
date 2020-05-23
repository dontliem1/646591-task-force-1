<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * UserFilterForm is the model behind the users filter form.
 */
class UserFilterForm extends Model
{
    public $sort;
    public $categories;
    private $_categories;
    public $isFree;
    public $isOnline;
    public $hasOpinions;
    public $isBookmarked;
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
}
