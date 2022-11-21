<?php

namespace frontend\modules\v1\controllers;

use common\components\ApiController;

class UserController extends ApiController
{

    public $modelClass  = 'common\models\User';

    public function actionIndex(){
        return 'User Comtroller';
    }

}