<?php

namespace pages\controllers;

use app\extended\web\Controller;
use pages\components\Pages;

class DefaultController extends Controller
{
    public function actionWordpress($page)
    {
        $p = (new Pages())->get($page);
        if (isset($p['content'])) {
            return $this->render('page_wrapper', [
                'content' => $p['content']
            ]);
        }
        return $this->goHome();
    }
}
