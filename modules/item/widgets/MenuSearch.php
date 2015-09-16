<?php


namespace app\modules\item\widgets;

use app\modules\search\forms\Filter;
use yii\bootstrap\Widget;

class MenuSearch extends Widget
{
    public $data;

    public function init()
    {

    }

    public function run()
    {
        $url = @\Yii::$app->request->getUrl();
        $showMenu = ($url == '/' || $url == '/home');
        if ($showMenu) {
            return false;
        }
        $model = new Filter([]);
        return $this->render('menu_search', [
            'model' => $model
        ]);
    }
}