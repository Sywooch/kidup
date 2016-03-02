<?php
namespace admin\controllers;

use notification\components\MailRenderer;
use notification\components\MailTemplates;
use notification\components\PushTemplates;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;

/**
 * MailController.
 */
class MailController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * Lists all MailTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $templates = MailTemplates::$templates;

        $data = [];
        foreach ($templates as $template => $information) {
            $data[] = array_merge(
                ['template' => $template],
                $information
            );
        }

        $searchModel = [
            'template' => null
        ];

        $dataProvider = new ArrayDataProvider([
            'key'=>'template',
            'allModels' => $data,
            'sort' => [
                'attributes' => ['template'],
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    private function renderTemplate($information, $template) {
        $vars = [];
        $title = null;
        $fill = true;
        $renderer = new MailRenderer();
        if (array_key_exists('title', $information)) {
            $title = $information['title'];
        }
        if (array_key_exists('variables', $information)) {
            foreach ($information['variables'] as $var) {
                $vars[$var] = "<b style='color: red;'>[" . $var . ']</b>';
            }
        }
        $renderer->setVariables($vars);
        $renderer->setTitle($title);
        $renderer->fillAutomatically();
        return $renderer->render($template);
    }

    public function actionView($id) {
        $templates = MailTemplates::$templates;
        if (array_key_exists($id, $templates)) {
            return $this->renderTemplate($templates[$id], $id);
        } else {
            // It could be a push fallback
            $pushTemplates = PushTemplates::$templates;
            foreach ($pushTemplates as $template => $information) {
                if ($information['fallback'] == $id) {
                    return $this->renderTemplate($pushTemplates[$template], $template);
                }
            }
        }
    }

}
