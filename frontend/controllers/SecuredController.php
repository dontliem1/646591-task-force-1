<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\User;

abstract class SecuredController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'controllers' => ['site'],
                        'actions' => ['index', 'signup'],
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            $this->redirect('tasks/');
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['tasks', 'users'],
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['tasks'],
                        'actions' => ['create'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role === User::ROLE_CUSTOMER;
                        }
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['index', 'signup'],
                        'roles' => ['?']
                    ],
                ]
            ]
        ];
    }
}