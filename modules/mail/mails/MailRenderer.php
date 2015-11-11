<?php
namespace mail\mails;

use mail\models\base\MailTemplate;
use mail\models\Mailer;
use Yii;
use yii\helpers\Json;

/**
 * Class for rendering e-mails.
 *
 * Class MailRenderer
 * @package mail\mails
 */
class MailRenderer
{
    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Convert the mail rendering to a string.
     *
     * @return string The e-mail represented in HTML.
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Render the e-mail and give back an HTML representation of the e-mail.
     *
     * @return string The e-mail represented in HTML.
     */
    public function render()
    {
        $mailer = \Yii::$app->mailer;
        $mailer->viewPath = $this->mail->getViewPath();
        $viewModel = Json::decode(Json::encode($this->mail));
        $mailer->htmlLayout = '@mail/views/layouts/html.twig';
        if ($this->mail->getTemplateId()) {
            // this is hacky, save tmp to the file system for yii to be able to render it
            $fileName = Yii::$aliases['@runtime'] . '/tmp-email-' . \Yii::$app->security->generateRandomString() . '.twig';
            $template = MailTemplate::findOne($this->mail->getTemplateId());
            file_put_contents($fileName, $template->template);
            $renderedTemplate = \Yii::$app->view->renderFile($fileName);
            unlink($fileName);
        } else {
            $renderedTemplate = \Yii::$app->view->renderFile($this->mail->getViewPath() . '/' . $this->mail->getTemplatePath(),
                ['vm' => $viewModel]);
        }
        $renderedLayout = \Yii::$app->view->renderFile($this->mail->getViewPath() . '/layouts/html.twig',
            ['content' => $renderedTemplate]);
        return $renderedLayout;
    }

}