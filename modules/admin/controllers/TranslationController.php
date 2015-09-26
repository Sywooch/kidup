<?php

namespace app\modules\admin\controllers;

use app\modules\admin\forms\Translate;
use app\modules\admin\models\I18nMessage;
use app\modules\admin\models\I18nSource;
use Yii;

class TranslationController extends Controller
{
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
