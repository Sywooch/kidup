<?php

namespace app\components;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        if(YII_ENV == 'prod'){
            $source = I18nSource::find()->where([
                'category' => $event->category,
            ])->one();
            if(count($source) == 0){
                $s = new I18nSource();
                $s->category = $event->category;
                $s->message = $event->message;
                $s->save();

                foreach (['da-DK', 'en-US'] as $lang) {
                    $m = new I18nMessage();
                    $m->setAttributes([
                        'id' => $s->id,
                        'language' => $lang,
                        'translation' => null
                    ]);
                    $m->save();
                }
            }
        }
    }
}