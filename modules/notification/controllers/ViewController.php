<?php
namespace notification\controllers;

use app\extended\web\Controller;
use notification\components\MailRenderer;
use notification\components\MailSender;
use notification\models\template\UserWelcomeRenderer;
use user\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class ViewController extends Controller
{

    public $layout;

    public function actionIndex($id)
    {
        $mailLog = MailLog::findOne($id);
        if ($mailLog == null) {
            throw new NotFoundHttpException("Email not found");
        }

        $mail = \mail\mails\MailType::getModel($mailLog->type);
        $mail->loadData($mailLog->data);
        $renderer = new MailRenderer($mail);
        $renderer->render();
    }

    public function actionLink($url, $mailId)
    {
        return \Yii::$app->view->renderFile('@notification-layouts/link.twig', [
            'url' => base64_decode($url),
            'appstore_google_url' => Yii::$app->params['appstore-google'],
            'appstore_ios_url' => Yii::$app->params['appstore-ios'],
        ]);
    }

    public function actionTest() {
        echo '<pre>';
        $subject = '{{ t(\'mail.booking_start_renter.p4\',
                            \'You can see the full details of the rental in the {app_link}.\', {
                            \'app_link\': \'<a href="\' ~ app_url ~ \'" target="_blank">\' ~ t("mail.links.app", \'app\') ~ \'</a>\'
                        }) | raw }}';

        $subject = str_replace("\n", '', $subject);
        preg_match_all('/t\s*\(\s*\'(.*?)\'\s*,\s*\'(.*?)\'/', $subject, $matches1);
        preg_match_all('/t\s*\(\s*\'(.*?)\'\s*,\s*"(.*?)"/', $subject, $matches2);
        preg_match_all('/t\s*\(\s*"(.*?)"\s*,\s*\'(.*?)\'/', $subject, $matches3);
        preg_match_all('/t\s*\(\s*"(.*?)"\s*,\s*"(.*?)"/', $subject, $matches4);
        $keys = array_merge($matches1[1], $matches2[1], $matches3[1], $matches4[1]);
        $translations = array_merge($matches1[2], $matches2[2], $matches3[2], $matches4[2]);
        $t = [];
        for ($i = 0; $i < count($keys); $i++) {
            $t[$keys[$i]] = $translations[$i];
        }
        print_r($t);
        die();

        preg_match_all('/[{]{2}\s*t[(]{1}(.*?)[)]{1}\s*[|]{1}(.*?)[}]{2}/mu', $subject, $matches);
        $translations = [];
        echo $subject . "\n";
        print_r($matches);
        foreach ($matches[1] as $tString) {
            $parts = explode(',', $tString);
            $key = trim($parts[0]);
            $tail = array_slice($parts, 1);
            $translation = join(',', $tail);

            // Chop off the quotation characters
            $key = trim($key);
            $translation = trim($translation);
            $firstChar = $key[0];


            $key = trim($key, $firstChar);
            $firstChar = $translation[0];
            $lastChar = $translation[strlen($translation) - 1];
            if ($firstChar == $lastChar) {
                $translation = trim($translation, $firstChar);
            } else {
                preg_match_all('/(.*)\s*,\s*[\[]{1}(.*?)[\]]{1}$/', $translation, $matches);
                $results = $matches[1];
                if (count($results) == 0) {
                    preg_match_all('/(.*)\s*,\s*[\{]{1}(.*?)[\}]{1}$/', $translation, $matches);
                    $results = $matches[1];
                }
                $translation = $results[0];
            }

            $translations[$key] = $translation;
        }

        print_r($translations);

        echo '</pre>';
        //$renderer = new UserWelcomeRenderer(User::find()->where(['email' => 'kevin91nl@gmail.com'])->one());
        //MailSender::send($renderer);
        //return $renderer->renderMail();
    }

}

