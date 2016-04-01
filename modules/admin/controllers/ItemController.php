<?php

namespace admin\controllers;

use admin\models\search\Item as ItemSearch;
use item\models\item\Item;
use message\models\conversation\ConversationFactory;
use message\models\message\MessageFactory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUnpublish($id)
    {
        /**
         * @var Item $item
         */
        $item = $this->findModel($id);
        $item->is_available = 0;
        if (!$item->save()) {
            \Yii::$app->session->addFlash("error", 'unpublishing failed');
            return $this->redirect(['index']);
        }
        $conv = (new ConversationFactory())->getOrCreateKidUpConversation($item->owner);
        (new MessageFactory())->addToConversation(
            \Yii::t('app.admin.conversation_message.unpublished_by_admin',
                "Hi there, we've unpublished you're item {item_name} temporarily. Please make sure it is up to date and well presented before publishing it again!",
                [
                    'item_name' => !empty($item->name) ? $item->name : $item->id
                ])
            , $conv, $conv->initiaterUser);
        return $this->redirect(['index']);
    }

    public function actionPublish($id)
    {
        /**
         * @var Item $item
         */
        $item = $this->findModel($id);
        $item->is_available = 1;
        if (!$item->save()) {
            \Yii::$app->session->addFlash("error", 'publishing failed');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
