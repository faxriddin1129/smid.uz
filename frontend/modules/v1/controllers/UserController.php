<?php

namespace frontend\modules\v1\controllers;


use common\components\ApiController;
use common\models\LoginForm;
use common\modules\user\models\User;
use common\modules\user\models\UserSearch;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\OptionsAction;

class UserController extends ApiController
{
    public $modelClass = User::class;
    public $searchModel = UserSearch::class;

    public function actions()
    {
        return [
            'options' => OptionsAction::class
        ];
    }

    public function actionIndex(){
        return "User v1 Controller";
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionSignIn()
    {
        $model = new LoginForm();
        $queryParams = Yii::$app->getRequest()->getBodyParams();
        $model->setAttributes($queryParams);
        $user = $model->login();
        if (!$user) {
            return $model;
        }

        return $user;
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionSignUp()
    {
        $model = new SignupForm();
        $queryParams = Yii::$app->getRequest()->getBodyParams();
        $model->setAttributes($queryParams);
        if ($model->signup()){
            return Yii::$app->user->identity;
        }

        return $model;
    }

    public function actionGetMe()
    {
        return \Yii::$app->user->identity;
    }


}
