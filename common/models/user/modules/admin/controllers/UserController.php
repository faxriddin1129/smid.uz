<?php

namespace common\modules\user\modules\admin\controllers;

use common\modules\hr\models\search\InstructionSearch;
use common\modules\user\forms\UserDetailUpdateFormAdmin;
use common\modules\user\forms\WorkingHourFormAdmin;
use common\modules\user\models\User;
use common\modules\user\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @package common\modules\user\modules\admin\controllers
 */
class UserController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'error'],
						'allow' => true,
					],
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Service models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}


	public function actionPrint()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->setPageSize(10000);

		$this->layout = '@backend/views/layouts/print';

		return $this->render('index-print', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}


	/**
	 * Displays a single Service model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * @param $id
	 * @return User|null
	 * @throws NotFoundHttpException
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}

	/**
	 * Creates a new Service model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserDetailUpdateFormAdmin();
        $model->scenario = UserDetailUpdateFormAdmin::SCENARIO_CREATE;

		if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                return $this->redirect(['/user/user/index']);
            }
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Service model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws \Exception
	 */
	public function actionUpdate($id)
	{

		$model = new UserDetailUpdateFormAdmin(['id' => $id]);
        $model->scenario = UserDetailUpdateFormAdmin::SCENARIO_UPDATE;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['/user/user/index']);
		}

		return $this->render('update', [
			'model' => $model->getModel(),
		]);
	}

	/**
	 * Deletes an existing Service model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}

		if ($id == 1) {
			return $this->refresh();
		}

		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}


    public function actionInstruction($id){

        $searchModel = new InstructionSearch(['user_id' => $id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('instruction', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


    public function actionWorkingHour($id){

        $model = new WorkingHourFormAdmin(['user_id' => $id]);

//        echo "<pre>";
//        print_r($model->getModel());
//        die();

        if ($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['/user/user/index']);
        }

        return $this->render('working-hour', [
            'model' => $model->getModel(),
        ]);

    }


}
