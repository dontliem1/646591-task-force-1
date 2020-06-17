<?php
namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

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
                        'roles' => ['@'],
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