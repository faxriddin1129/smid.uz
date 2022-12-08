<?php

namespace common\modules\user\modules\admin\controllers;

use Yii;
use common\modules\user\models\UserAction;
use common\modules\user\models\UserActionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserActionController implements the CRUD actions for UserAction model.
 */
class UserActionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'view',
                            'activate',
                            'update',
                            'rejected'
                        ],
                        'allow' => true,
                        'roles' => ['moderator', 'admin']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserAction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->select('exhibition_id,"get_params"->>\'organization_id\' as get_params')
            ->andWhere(['action' => 'organization', 'controller' => 'exhibition'])
            ->where('get_params->>\'organization_id\' IS NOT NULL AND exhibition_id IS NOT NULL')
            ->groupBy(['"get_params"->>\'organization_id\'', 'exhibition_id']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionByOrganization($exhibition_id, $id) {
        $searchModel = new UserActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query
            ->select('exhibition_id,user_id')
            ->where('get_params->>\'organization_id\' =\'' . $id . '\'')
            ->andWhere(['action' => 'organization', 'controller' => 'exhibition', 'exhibition_id' => $exhibition_id])
            ->groupBy(['user_id', 'exhibition_id']);

        return $this->render('by-organization', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organizationId' => $id
        ]);
    }

    public function actionByUser($id) {
        $searchModel = new UserActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query
            ->select('exhibition_id,user_id, count(id) as activities')
            ->andWhere(['exhibition_id' => $id])
            ->orderBy(['activities' => SORT_DESC])
            ->groupBy(['user_id', 'exhibition_id']);

        return $this->render('by-user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organizationId' => $id
        ]);
    }

    public function actionRoad($exhibition_id, $user_id) {
        $searchModel = new UserActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query
            ->andWhere(['exhibition_id' => $exhibition_id, 'user_id' => $user_id]);

        return $this->render('user-road', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

        public function actionPrint($id)
        {
        $searchModel = new UserActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query
            ->select('exhibition_id,user_id')
            ->where('get_params->>\'organization_id\' =\'' . $id . '\'')
            ->andWhere(['action' => 'organization', 'controller' => 'exhibition'])
            ->groupBy(['user_id', 'exhibition_id']);
        $dataProvider->pagination->setPageSize(10000);

        $this->layout = '@backend/views/layouts/print';

        return $this->render('by-organization-print', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single UserAction model.
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
     * Creates a new UserAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserAction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserAction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserAction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserAction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
