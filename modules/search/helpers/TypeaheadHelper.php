<?php
namespace app\modules\search\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "location".
 */
class TypeaheadHelper
{
    public static function getConfig($array)
    {
        return ArrayHelper::merge($array, [
            'options' => ['placeholder' => \Yii::t('home', 'What do you like to get your child?')],
            'pluginOptions' => ['highlight' => true, 'hint' => true],
            'dataset' => [
                [
                    'remote' => [
                        'url' => Url::to('@web/search/auto-complete/index?q=%q'),
                        'wildcard' => '%q'
                    ],
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'limit' => 5,
                    'display' => 'text',
                    'templates' => [
                        'notFound' => '<div class="text-danger" style="padding:0 8px">'.\Yii::t('home', "We couldn't find that, perhaps try Stroller, Trampoline or Toy?").'</div>',
                        'suggestion' => new \yii\web\JsExpression("Handlebars.compile('<div>{{text}}</div>')")
                    ]
                ],

            ]
        ]);
    }
}