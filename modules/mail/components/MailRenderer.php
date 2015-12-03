<?php
namespace mail\components;

use mail\models\UrlFactory;
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

    /** @var \mail\mails\Mail */
    private $mail;

    /**
     * MailRenderer constructor.
     *
     * @param \mail\mails\Mail $mail Mail to render.
     */
    public function __construct(\mail\mails\Mail $mail)
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
        WidgetFactory::registerWidgets();
        $mailer = \Yii::$app->mailer;
        $mailer->viewPath = $this->mail->getViewPath();
        $viewModel = Json::decode(Json::encode($this->mail));
        $viewModel['seeInBrowserUrl'] = UrlFactory::seeInBrowser($this->mail->mailId);
        $viewModel['changeSettingsUrl'] = UrlFactory::changeSettings();
        $mailer->htmlLayout = '@mail/views/layouts/html.twig';
        $renderedTemplate = \Yii::$app->view->renderFile($this->mail->getViewPath() . '/' . $this->mail->getTemplatePath(),
            ['vm' => $viewModel]);
        $params = ['content' => $renderedTemplate, 'vm' => $viewModel];
        $renderedLayout = \Yii::$app->view->renderFile($this->mail->getViewPath() . '/layouts/html.twig', $params);
        return $renderedLayout;
    }

}