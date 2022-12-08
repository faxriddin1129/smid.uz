<?php

namespace frontend\modules\v1\controllers;

use common\components\CrudController;
use common\models\Expencess;
use common\models\search\ExpencessSearch;

class ExpencessController extends CrudController
{

    public $modelClass = Expencess::class;
    public $searchModel = ExpencessSearch::class;


    public function actionIndex()
    {
        $search = new ExpencessSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }

}