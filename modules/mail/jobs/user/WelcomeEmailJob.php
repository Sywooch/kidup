<?php
//
//namespace mail\jobs\user;
//
//use app\components\Job;
//use \mail\models\Mailer;
//use \mail\models\Token;
//use \user\models\User;
//use yii\helpers\Url;
//
//class WelcomeEmailJob extends Job{
//
//    public $user_id;
//    public $email;
//
//    public function handle(){
//        $user = User::findOne($this->user_id);
//        $token = new Token();
//        $token->setAttributes([
//            'user_id' => $user->id,
//            'type' => Token::TYPE_CONFIRMATION,
//        ]);
//        $token->save();
//        $url = $token->getUrl();
//
//        return (new Mailer())->sendMessage([
//            'email' => $user->email,
//            'subject' => 'Kom godt i gang p� KidUp',
//            'type' => (new Mailer())::USER_WELCOME,
//            'params' => [
//                'profileName' => $user->profile->first_name,
//            ],
//            'urls' => [
//                'verify' => $url,
//                'profile' => Url::to('@web/user/settings/profile', true),
//                'search' => Url::to('@web/search', true),
//            ]
//        ]);
//    }
//
//    private function test(){
//        new WelcomeEmailJob([
//            'user_id' => 1,
//            'email' => 'simpi_123@hotmail.com'
//        ]);
//    }
//
//}