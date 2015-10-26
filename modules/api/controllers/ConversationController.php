<?php
namespace api\controllers;

use api\models\Conversation;

class ConversationController extends Controller
{
    public function init(){
        $this->modelClass = Conversation::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => [''],
            'user' => ['index', 'view', 'create', 'update']
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['update']);
        return $actions;
    }
}