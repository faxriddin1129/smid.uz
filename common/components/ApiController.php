<?php

namespace common\components;

use yii\data\ActiveDataFilter;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\RateLimiter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\ViewAction;
use yii\web\Response;

/**
 * Class ApiController
 *
 * @package common\components
 */
abstract class ApiController extends  Controller
{
    /**
     * @var
     */
    public $modelClass;

    /**
     * @var
     */
    public $searchModel;

    /**
     * @var array
     */
    public $serializer = [
        'class' => 'common\components\Serializer'
    ];

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
                'languages' => [
                    'ru',
                    'en',
                    'uz',
                ],
                'formatParam' => '_f',
                'languageParam' => '_l',
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
        ]);

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => [
                'user/login',
                'user/signup',
                '*/options',
            ],
            'optional' => [
                'default/*'
            ],
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => '*',
                'Access-Control-Request-Method' => ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'OPTIONS', 'DELETE'],
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Expose-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Allow-Methods' => ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'OPTIONS', 'DELETE'],
                'Access-Control-Allow-Headers' => ['Authorization', 'X-Requested-With', 'content-type'],
            ],
        ];

        return  $behaviors;

    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $this->searchModel,
                ]
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass
            ],
            'options' => [
                'class' => OptionsAction::class,
            ]
        ];
    }

}