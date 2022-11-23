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

                'OPTIONS index' => 'options',
                'GET index' => 'index',
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/site',
            'pluralize' => false,
            'patterns' => [
                'OPTIONS <action>' => 'options',
                'OPTIONS' => 'options',
                'GET' => 'index',
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/task',
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
