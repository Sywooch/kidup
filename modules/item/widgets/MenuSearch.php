<?php


namespace app\modules\item\widgets;

use app\modules\search\models\SearchModel;
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
        $showMenu = ($url == '/' || $url == '/home' || strpos($url, '/search') !== false);
        if ($showMenu) {
            return false;
        }
        $model = new SearchModel([]);
        return $this->render('menu_search', [
            'model' => $model
        ]);
    }
}