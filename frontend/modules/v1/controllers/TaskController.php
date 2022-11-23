<?php

namespace frontend\modules\v1\controllers;

use common\components\CrudController;
use frontend\models\Task;
use frontend\models\TaskSearch;

class TaskController extends CrudController
{

    public $modelClass = Task::class;
    public $searchModel = TaskSearch::class;


    public function actionIndex()
    {
        $search = new TaskSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }

//    public function actionCreate(){
//        return 'null';
//    }


}