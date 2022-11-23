<?php

namespace frontend\modules\v1\controllers;

use common\components\ApiController;

class SiteController extends ApiController
{

    public $modelClass  = 'common\models\User';

    public function actionIndex(){
        return '\Yii::$app->user->identity';
    }

}