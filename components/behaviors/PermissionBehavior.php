<?php
namespace app\components\behaviors;

use app\components\Permission;
use app\components\PermissionException;
use app\models\BaseActiveRecord;
use yii\base\Behavior;

/**
 * Class Permission
 *
 * @property BaseActiveRecord $owner
 */
class PermissionBehavior extends Behavior
{
    /**
     * @var array Attributes array
     */
    public $permissions = [
        Permission::ACTION_READ => Permission::GUEST,
        Permission::ACTION_DELETE => Permission::ROOT,
        Permission::ACTION_CREATE => Permission::ROOT,
        Permission::ACTION_UPDATE => Permission::ROOT,
    ];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'checkCreate',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            BaseActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function checkCreate($event)
    {
        if (!$this->validatePermission($this->permissions[Permission::ACTION_CREATE]) ||
            ($this->owner->hasMethod('canCreate') ? !$this->owner->canCreate($this->owner) : false)) {
            throw new PermissionException("You are not allowed to create an " . $this->owner->friendlyClassName());
        }
    }

    public function beforeDelete($event)
    {
        if (!$this->validatePermission($this->permissions[Permission::ACTION_DELETE]) ||
            ($this->owner->hasMethod('canDelete') ? !$this->owner->canDelete($this->owner) : false)) {
            throw new PermissionException("You are not allowed to delete this " . $this->owner->friendlyClassName());
        }
    }

    public function afterFind($event)
    {
        if (!$this->validatePermission($this->permissions[Permission::ACTION_READ]) ||
        ($this->owner->hasMethod('canRead') ? !$this->owner->canRead($this->owner) : false)) {
            throw new PermissionException("You are not allowed to read this " . $this->owner->friendlyClassName());
        }
    }

    public function beforeUpdate($event)
    {
        if (!$this->validatePermission($this->permissions[Permission::ACTION_READ]) ||
            ($this->owner->hasMethod('canUpdate') ? !$this->owner->canUpdate($this->owner) : false)) {
            throw new PermissionException("You are not allowed to update this " . $this->owner->friendlyClassName());
        }
    }

    private function validatePermission($type)
    {
        if ($type == Permission::GUEST) {
            return Permission::isGuest();
        }
        if ($type == Permission::USER) {
            return Permission::isUser();
        }
        if ($type == Permission::OWNER) {
            return $this->owner->isOwner();
        }
        if ($type == Permission::ADMIN) {
            return Permission::isAdmin();
        }
        if ($type == Permission::ROOT) {
            return Permission::isRoot();
        }
        return false;
    }
}