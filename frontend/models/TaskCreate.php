<?php
namespace frontend\models;

use Yii;
use frontend\models\Task;

/**
 * Task creation
 */
class TaskCreate extends Task
{
    /**
     * @var UploadedFile[]
     */
    public $uploadedFiles = [];


    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Мне нужно',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'uploadedFiles' => 'Файлы',
            'address' => 'Локация',
            'budget' => 'Бюджет',
            'expire' => 'Срок исполнения',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints()
    {
        return [
            'name' => 'Кратко опишите суть работы',
            'description' => 'Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться',
            'category_id' => 'Выберите категорию',
            'uploadedFiles' => 'Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу',
            'address' => 'Укажите адрес исполнения, если задание требует присутствия',
            'budget' => 'Не заполняйте для оценки исполнителем',
            'expire' => 'Укажите крайний срок исполнения',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category_id', 'uploadedFiles', 'address', 'budget', 'expire'], 'safe'],
            [['name', 'description', 'category_id'], 'required'],
            [['name', 'description'], 'trim'],
            ['name', 'string', 'min' => 10, 'max' => 255],
            ['uploadedFiles', 'file', 'maxFiles' => 20],
            ['description', 'string', 'min' => 30],
            [['category_id'], 'exist', 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            ['budget', 'integer', 'min' => 1],
            ['expire', 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate('uploadedFiles')) {
            foreach ($this->uploadedFiles as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}