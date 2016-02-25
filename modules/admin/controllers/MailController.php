<?php
namespace admin\controllers;

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
            'template' => null,
            'variables' => null
        ];

        $dataProvider = new ArrayDataProvider([
            'key'=>'template',
            'allModels' => $data,
            'sort' => [
                'attributes' => ['template', 'variables'],
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id) {
        // @todo make mail renderer
        $templates = MailTemplates::$templates;
        if (array_key_exists($id, $templates)) {
            $vars = [];
            if (array_key_exists('variables', $templates[$id])) {
                foreach ($templates[$id]['variables'] as $var) {
                    $vars[$var] = '<b style="color: red;">[' . $var . ']</b>';
                }
            }
            MailRenderer::render($id, $vars);
            die();
            return \Yii::$app->view->renderFile('@notification-mail/' . $id . '.twig', $vars);
        } else {
            // It could be a push fallback
            $pushTemplates = PushTemplates::$templates;
            foreach ($pushTemplates as $template => $information) {
                if ($information['fallback'] == $id) {
                    $vars = [];
                    if (array_key_exists('variables', $pushTemplates[$template])) {
                        foreach ($pushTemplates[$template]['variables'] as $var) {
                            $vars[$var] = '<b style="color: red;">[' . $var . ']</b>';
                        }
                    }
                    return \Yii::$app->view->renderFile('@notification-mail/' . $id . '.twig', $vars);
                }
            }
        }
    }

}
