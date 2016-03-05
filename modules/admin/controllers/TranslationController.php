<?php

namespace admin\controllers;

use admin\forms\Translate;
use admin\models\I18nMessage;
use admin\models\I18nSource;
use admin\models\search\I18nMessageSearch;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class TranslationController extends Controller
{
    public function actionIndex($language = 'da-DK')
    {
        $searchModel = new I18nMessageSearch();
        $searchModel->language = $language;
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
        $menuItems = [];
        foreach(["da-DK", 'en-US'] as $lang) {
            $menuItems[] = [
                'label' => $lang,
                'url' => Url::to(['index', 'language' => $lang]),
                'active' => $lang == $language
            ];
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'language' => $language,
            'menuItems' => $menuItems
        ]);
    }

    public function actionSaveTranslate()
    {
        if(!Yii::$app->request->post('hasEditable', false))
            return;
        $key = json_decode(Yii::$app->request->post('editableKey', false), true);
        if(empty($key))
            return;
        /** @var I18nMessage $model */
        $model = I18nMessage::findOne($key);
        if(Model::loadMultiple([Yii::$app->request->post('editableIndex', 0) => $model], Yii::$app->request->post()) && $model->save())
        {
            echo Json::encode(['output' => Html::encode($model->translation)]);
        } else
            echo Json::encode(['message' => 'Ошибки при вводе']);
    }

    public function actionTranslate($id)
    {
        $source = I18nSource::findOne($id);
        $form = new Translate([
            'source' => $source,
            'language' => 'da-DK'
        ]);

        if(Yii::$app->request->isPost){
            $form->load(Yii::$app->request->post());

            if($form->save()){

            }
        }

        return $this->render('translate', [
            "model" => $form
        ]);
    }
}
