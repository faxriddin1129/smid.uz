<?php

namespace frontend\controllers;


use Yii;
use yii\web\Controller;
use common\models\LoginForm;
use frontend\models\SignupForm;


class SiteController extends Controller
{

    public function actionIndex()
    {
        return "Welcome to Textile Api v1";
    }


    public function actionLogin()
    {

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(),'') && $model->login()) {
            return Yii::$app->user->identity;
        }

        return $model;
    }


    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(),'') && $model->signup()) {
            return Yii::$app->user->identity;
        }

        return $model;
    }

}
