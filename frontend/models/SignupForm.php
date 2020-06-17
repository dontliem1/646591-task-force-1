<?php
namespace frontend\models;

use Yii;
use Yii\base\Model;
use frontend\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $name;
    public $city_id;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'city_id' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'city_id', 'password'], 'safe'],
            [['email', 'name', 'city_id', 'password'], 'required'],
            
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким Email уже существует.'],

            ['name', 'trim'],
            ['name', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['name', 'string', 'max' => 255],

            [['city_id'], 'integer'],
            [['city_id'], 'exist', 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],

            ['password', 'string', 'min' => 8],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->cityId = $this->city;
        $user->setPassword($this->password);
        return $user->save();

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}