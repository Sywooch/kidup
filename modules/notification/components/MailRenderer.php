<?php
namespace notification\components;

use notifications\models\UrlFactory;
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
        $viewModel = $this->getViewModel();
        $mailer->htmlLayout = '@notification/views/layouts/html.twig';
        $renderedTemplate = $this->renderPartial();
        $params = ['content' => $renderedTemplate, 'vm' => $viewModel];
        $renderedLayout = \Yii::$app->view->renderFile($this->mail->getViewPath() . '/layouts/html.twig', $params);
        return $renderedLayout;
    }

    /**
     * Render the mail without the main layout.
     *
     * @return string HTML output.
     */
    public function renderPartial() {
        $viewModel = $this->getViewModel();
        return \Yii::$app->view->renderFile($this->mail->getViewPath() . '/' . $this->mail->getTemplatePath(),
            ['vm' => $viewModel]);
    }

    /**
     * Get the view model representation of the mail.
     *
     * @return mixed View model representation.
     */
    private function getViewModel() {
        $viewModel = Json::decode(Json::encode($this->mail));
        $viewModel['seeInBrowserUrl'] = UrlFactory::seeInBrowser($this->mail->mailId);
        $viewModel['changeSettingsUrl'] = UrlFactory::changeSettings();
        return $viewModel;
    }

}