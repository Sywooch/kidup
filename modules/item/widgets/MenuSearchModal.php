<?php


namespace app\modules\item\widgets;

use app\modules\search\forms\Filter;
use yii\bootstrap\Widget;

class MenuSearchModal extends Widget
{
    public $data;

    public function init()
    {

    }

    public function run()
    {
        $model = new Filter([]);
        return $this->render('menu_search_modal', [
            'model' => $model
        ]);
    }
}