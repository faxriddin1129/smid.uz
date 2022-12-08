<?php

namespace frontend\modules\v1\controllers;

use common\components\CrudController;
use common\models\Product;
use common\models\search\ProductSearch;
use yii\web\NotFoundHttpException;

class ProductController extends CrudController
{

    public $modelClass = Product::class;
    public $searchModel = ProductSearch::class;


    public function actionIndex()
    {
        $search = new ProductSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }

    public function actionActive($id){
        $product = Product::findOne(['id' => $id]);
        if (!$product){
            throw new NotFoundHttpException('Product not found!');
        }

        $product->updateAttributes([
            'status' => Product::STATUS_ACTIVE
        ]);

        return $product;

    }

    public function actionInActive($id)
    {
        $product = Product::findOne(['id' => $id]);
        if (!$product){
            throw new NotFoundHttpException('Product not found!');
        }

        $product->updateAttributes([
            'status' => Product::STATUS_INACTIVE
        ]);

        return $product;
    }

}