<?php
namespace api\controllers;

use api\models\Review;
use api\models\User;
use user\forms\Registration;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends Controller
{
    public function init()
    {
        $this->modelClass = User::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'view', 'create', 'review'],
            'user' => ['update', 'me']
        ];
    }


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    /**
     * Registration of a user
     * @param string $email
     * @param string $password
     * return array|User
     */
    public function actionCreate()
    {
        $params = \Yii::$app->request->post();
        $registration = new Registration();
        $registration->email = $params['email'];
        $registration->password = $params['password'];
        if ($registration->register()) {
            return User::findOne(['email' => $params['email']]);
        }
        return [
            'success' => false,
            'errors' => $registration->getErrors()
        ];
    }

    /**
     * Returns the object of the logged in user
     */
    public function actionMe(){
        $user = User::findOne(\Yii::$app->user->id);
        if($user == null){
            throw new NotFoundHttpException("User not found");
        }
        return $user;
    }

    public function actionReview($id){
        return new ActiveDataProvider([
            'query' => Review::find()->where(['reviewed_id' => $id, 'type' => Review::TYPE_USER_PUBLIC])
        ]);
    }
}