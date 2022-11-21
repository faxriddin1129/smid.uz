<?php

namespace common\components;

use yii\data\ActiveDataFilter;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\helpers\ArrayHelper;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\UpdateAction;
use yii\rest\ViewAction;
use yii\web\Response;

/**
 * Class ApiController
 *
 * @package common\components
 */
abstract class CrudController extends ApiController
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
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
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
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass
            ],
            'options' => [
                'class' => OptionsAction::class,
            ]
        ];
    }

}