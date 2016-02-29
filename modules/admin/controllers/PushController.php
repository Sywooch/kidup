<?php
namespace admin\controllers;

use notification\components\PushTemplates;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use notifications\components\MailTemplates;

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
        foreach ($templates as $template => $information) {
            $vars = [];
            if (array_key_exists('variables', $information)) {
                foreach ($information['variables'] as $var) {
                    $vars[$var] = '[' . $var . ']';
                }
            }
            $information['message'] = Yii::t('push.' . $template . '.message', $information['message'], $vars);
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
