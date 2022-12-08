<?php

namespace frontend\modules\v1\controllers;

use common\components\CrudController;
use common\models\Order;
use common\models\search\OrderSearch;

class OrderController extends CrudController
{

    public $modelClass = Order::class;
    public $searchModel = OrderSearch::class;


    public function actionIndex()
    {
        $search = new OrderSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }

}