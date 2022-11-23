<?php

namespace frontend\modules\v1\controllers;

use common\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use yii\rest\Controller;

class UserController extends Controller
{

    public $modelClass  = 'common\models\User';

    public function actionLogin()
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


    public function actionSignup()
    {
        $model = new SignupForm();
        $queryParams = Yii::$app->getRequest()->getBodyParams();
        $model->setAttributes($queryParams);
        if ($model->signup()){
            return Yii::$app->user->identity;
        }

        return $model;
    }

}