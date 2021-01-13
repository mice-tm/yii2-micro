<?php

namespace app\controllers;

use app\forms\DiscountForm;
use app\models\User;
use app\models\Discount;
use app\services\Discount as DiscountService;
use app\services\DiscountServiceInterface;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;
use yii\web\Response;

class DiscountsController extends ActiveController
{
    public $modelClass = 'app\models\Discount';
    
    protected DiscountServiceInterface $service;

    public function __construct($id, $module, $config = [])
    {
        $this->service = \Yii::$container->get(DiscountServiceInterface::class);
        parent::__construct($id, $module, $config);
    }
    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        $actions['options'] = [
            'class' => OptionsAction::class,
            'collectionOptions' => ['POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['POST', 'HEAD', 'OPTIONS'],
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
                        'POST',
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
    
    /**
     * @return Discount
     * @throws \HttpInvalidParamException
     */
    public function actionGenerate()
    {
        $form = new DiscountForm();
        $form->discount = \Yii::$app->request->post('discount');
        
        return $this->service->generate($form, Discount::find());
    }
    
    public function actionApply()
    {
        return $this->service->apply(\Yii::$app->request->post(), Discount::find());
    }
}
