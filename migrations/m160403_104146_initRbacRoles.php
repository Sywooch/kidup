<?php

use yii\db\Migration;
use \user\models\user\User;

class m160403_104146_initRbacRoles extends Migration
{
    public function up()
    {
        $this->alterColumn('user', 'role', \yii\db\Schema::TYPE_STRING);
        User::updateAll([
            'role' => User::ROLE_USER
        ], "role = 0"
        );
        User::updateAll([
            'role' => User::ROLE_ADMIN
        ], "role = 9"
        );
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $auth->removeAll();
        $user = $auth->createRole(User::ROLE_USER);
        $auth->add($user);

        $admin = $auth->createRole(User::ROLE_ADMIN);
        $auth->add($admin);

        $auth->addChild($admin, $user);
        $auth->assign($admin, 1);
        $auth->assign($user, 3);

        $rule = new \app\components\rbac\OwnModelRule();
        $auth->add($rule);
        $role = $auth->getRole(User::ROLE_USER);
        $editOwnModelPermission = $auth->createPermission('editOwnModel');
        $editOwnModelPermission->ruleName = $rule->name;

        $auth->add($editOwnModelPermission);
        $auth->addChild($role, $editOwnModelPermission);

        $rule = new \booking\rbac\BookingItemOwnerRule();
        $auth->add($rule);
        $role = $auth->getRole(User::ROLE_USER);

        $editOwnModelPermission = $auth->createPermission('editBookingAsOwner');
        $editOwnModelPermission->ruleName = $rule->name;

        $auth->add($editOwnModelPermission);
        $auth->addChild($role, $editOwnModelPermission);

        $rule = new \booking\rbac\BookingItemRenterRule();
        $auth->add($rule);
        $role = $auth->getRole(User::ROLE_USER);

        $editOwnModelPermission = $auth->createPermission('editBookingAsRenter');
        $editOwnModelPermission->ruleName = $rule->name;

        $auth->add($editOwnModelPermission);
        $auth->addChild($role, $editOwnModelPermission);
    }

    public function down()
    {
        echo "m160403_104146_initRbacRoles cannot be reverted.\n";

        return false;
    }
}
