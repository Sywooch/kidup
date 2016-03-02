<?php
namespace admin\controllers;

use notification\components\MailTemplates;
use notification\components\PushRenderer;
use notification\components\PushTemplates;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;

/**
 * PushController.
 */
class PushController extends Controller
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
        $templates = PushTemplates::$templates;

        $data = [];
        $renderer = new PushRenderer();
        foreach ($templates as $template => $information) {
            $vars = [];
            if (array_key_exists('variables', $information)) {
                foreach ($information['variables'] as $var) {
                    $vars[$var] = '[' . $var . ']';
                }
            }
            $renderer->setVariables($vars);
            $information['message'] = $renderer->render($template);
            $data[] = array_merge(
                ['template' => $template],
                $information
            );
        }

        $searchModel = [
            'template' => null,
            'fallback' => null,
            'message' => null
        ];

        $dataProvider = new ArrayDataProvider([
            'key'=>'template',
            'allModels' => $data,
            'sort' => [
                'attributes' => ['template', 'message', 'fallback', 'variables'],
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

}
