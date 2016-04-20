<?php
namespace api\v2\controllers;

use api\v2\models\Review;
use api\v2\models\User;
use app\components\Event;
use user\models\token\Token;
use user\forms\Registration;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function init()
    {
        $this->modelClass =  User::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'view', 'create', 'reviews', 'options', 'recover'],
            'user' => ['update', 'me', 'verify-phone-number']
        ];
    }


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
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
            $user = User::findOne(['email' => $params['email']]);
            // should have worked
            $user->profile->first_name = $params['first_name'];
            $user->profile->last_name = $params['last_name'];
            $user->profile->save();
            return $user;
        }
        return [
            'success' => false,
            'errors' => $registration->getErrors()
        ];
    }

    public function actionUpdate($id)
    {
        $user = User::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        if ($id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException(\Yii::$app->user->id);
        }
        /**
         * @var User $user
         */
        $user->load(\Yii::$app->request->getBodyParams());
        $profileParams = [
            'language' => \Yii::$app->request->getBodyParam('language'),
            'first_name' => \Yii::$app->request->getBodyParam('first_name'),
            'last_name' => \Yii::$app->request->getBodyParam('last_name'),
            'description' => \Yii::$app->request->getBodyParam('description'),
            'phone_country' => \Yii::$app->request->getBodyParam('phone_country'),
            'phone_number' => \Yii::$app->request->getBodyParam('phone_number'),
        ];
        $user->profile->setAttributes($profileParams);
        $user->profile->save();
        $user->save();
        return $user;
    }

    /**
     * Returns the object of the logged in user
     */
    public function actionMe()
    {
        $user = User::findOne(\Yii::$app->user->id);
        if ($user == null) {
            throw new NotFoundHttpException("User not found");
        }
        return $user;
    }

    public function actionView($id, $innerjoin = '')
    {
        return User::findOne($id);
    }

    public function actionReviews($id)
    {
        return new ActiveDataProvider([
            'query' => Review::find()->where(['reviewed_id' => $id, 'type' => Review::TYPE_USER_PUBLIC])
        ]);
    }

    public function actionVerifyPhoneNumber($code = false)
    {
        $profile = \Yii::$app->user->identity->profile;
        if ($code == false) {
            if(!$profile->isValidPhoneNumber()){
                return ['success' => false, 'message' => 'Phone number not valid.'];
            }
            if($profile->phone_verified){
                return ['success' => false, 'message' => 'Already has valid phone number.'];
            }
            $tokens = \user\models\token\Token::find()->where([
                'user_id' => \Yii::$app->user->id,
                'type' => Token::TYPE_PHONE_CODE
            ])->andWhere('created_at > :time')->addParams([':time' => time()-5*60])->count();
            if($tokens > 0){
                throw new BadRequestHttpException('Token was requested less then 5 minutes ago.');
            }

            Token::deleteAll([
                'user_id' => \Yii::$app->user->id,
                'type' => Token::TYPE_PHONE_CODE
            ]);
            $token = \Yii::createObject([
                'class' => Token::className(),
                'user_id' => \Yii::$app->user->id,
                'type' => Token::TYPE_PHONE_CODE,
            ]);
            $token->save();
            if ($profile->sendPhoneVerification($token)) {
                return ['success' => true, 'message' => "Verification code send"];
            } else {
                throw new BadRequestHttpException();
            }
        } else {
            $token = Token::findOne([
                'user_id' => \Yii::$app->user->id,
                'code' => $code,
                'type' => Token::TYPE_PHONE_CODE
            ]);
            if ($token == null) {
                return ['success' => false, 'message' => 'Code not found'];
            } else {
                $p = \Yii::$app->user->identity->profile;
                $p->setScenario('phonecode');
                $p->phone_verified = 1;
                return ['success' => $p->save()];
            }
        }
    }

    public function actionRecover() {
        $email = \Yii::$app->request->getQueryParam('email');
        $u = \user\models\user\User::findOneOr404([
            'email' => $email
        ]);
        Event::trigger($u, \user\models\user\User::EVENT_USER_REQUEST_RECOVERY);
        return ['success' => true];
    }

}