<?php

namespace frontend\modules\v1\controllers;

use common\components\CrudController;
use common\models\Category;
use common\models\search\CategorySearch;

class CategoryController extends CrudController
{

    public $modelClass = Category::class;
    public $searchModel = CategorySearch::class;


    public function actionIndex()
    {
        $search = new CategorySearch();
        return $search->search(\Yii::$app->request->queryParams);
    }

}