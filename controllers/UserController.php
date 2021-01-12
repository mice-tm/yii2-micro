<?php

namespace app\controllers;

use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;
use yii\web\Response;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        $actions['options'] = [
            'class' => OptionsAction::class,
            'collectionOptions' => ['GET', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    protected function verbs()
    {
        return array_merge(['options' => ['OPTIONS']], parent::verbs());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::class,
                'cors'  => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => [
                        'GET',
                        'HEAD',
                        'OPTIONS',
                    ],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                ]
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml'  => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class'   => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }

    public function actionIndex()
    {
        return ['yo index'];
    }

    public function actionGreet()
    {
        return ['yo greet'];
    }
}
