<?php


namespace search\widgets\menu;

use search\forms\Filter;
use yii\bootstrap\Widget;

class MenuSearch extends Widget
{
    public $data;

    public function init()
    {

    }

    public function run()
    {
        $showMenu = \Yii::$app->urlHelper->isHome();
        if ($showMenu) {
            return false;
        }
        return $this->renderFile('@search/widgets/menu/menu_search.twig', [
            'model' => []
        ]);
    }
}