<?php
namespace notification\models\template;

use notification\models\TemplateRenderer;
use user\models\User;

class UserWelcomeRenderer extends TemplateRenderer {

    public $template = 'user_welcome';

    public function __construct(User $user) {
        parent::__construct();
        $this->mailRenderer->loadUser($user);
        $this->pushRenderer->loadUser($user);
    }

}