<?php

namespace frontend\modules\v1\controllers;

use common\components\ApiController;
use frontend\models\Task;
use frontend\models\TaskSearch;

class TaskController extends ApiController
{

    public $modelClass = Task::class;
    public $searchModel = TaskSearch::class;


    public function actionIndex(): \yii\data\ActiveDataProvider
    {
        $search = new TaskSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }


}