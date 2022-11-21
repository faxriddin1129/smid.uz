<?php

namespace frontend\modules\v1;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => [
                'user/sign-in',
                'user/index',
                'user/sign-up',
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
                'Origin' => static::allowedDomains(),
                'Access-Control-Request-Method' => ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'OPTIONS', 'DELETE'],
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Expose-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Allow-Methods' => ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'OPTIONS', 'DELETE'],
                'Access-Control-Allow-Headers' => ['Authorization', 'X-Requested-With', 'content-type'],
            ],
        ];

        return $behaviors;
    }


    /**
     * @var array
     */
    public static $urlRules = [
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1',
            'pluralize' => false,
            'patterns' => [
                'GET' => 'default/index',

                'GET clear-cache' => 'default/clear-cache',
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/user',
            'pluralize' => false,
            'patterns' => [
                'OPTIONS <action>' => 'options',
                'OPTIONS ' => 'options',

                'OPTIONS sign-in' => 'options',
                'POST sign-in' => 'sign-in',

                'OPTIONS sign-up' => 'options',
                'POST sign-up' => 'sign-up',

                'OPTIONS get-me' => 'options',
                'GET get-me' => 'get-me',
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/provider',
            'pluralize' => false,
            'patterns' => [
                'OPTIONS <action>' => 'options',
                'OPTIONS' => 'options',

                'POST' => 'create',
                'GET' => 'index',

                'OPTIONS <id:\d+>' => 'options',
                'PUT <id:\d+>' => 'update',
                'GET <id:\d+>' => 'view',
                'DELETE <id:\d+>' => 'delete',
            ]
        ],



    ];


    /**
     * @return array
     */
    public static function allowedDomains()
    {
        return [
            '*',
        ];
    }
}
