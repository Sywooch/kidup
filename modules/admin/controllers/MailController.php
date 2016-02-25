<?php

namespace admin\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use mail\components\MailTemplates;

/**
 * MailTemplateController implements the CRUD actions for MailTemplate model.
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
        $templates = MailTemplates::$mails;

        $data = [];
        foreach ($templates as $template => $variables) {
            $data[] = [
                'template' => $template,
                'variables' => $variables
            ];
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
        MailTemplates::loadAliases();
        $templates = MailTemplates::$mails;
        if (array_key_exists($id, $templates)) {
            $vars = [];
            foreach ($templates[$id] as $var) {
                $vars[$var] = '<b style="color: red;">[' . $var .']</b>';
            }
            echo \Yii::$app->view->renderFile('@mail-layouts/' . $id . '.twig', $vars);
        }
    }

}
