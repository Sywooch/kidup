<?php
namespace notification\components\renderer;

use booking\models\base\Payout;
use notification\components\Renderer;
use user\models\User;

class UserRenderer
{

    /**
     * Load an owner.
     *
     * @param User $user The owner.
     * @return array All the render variables.
     */
    public function loadOwner(User $user) {
        $result = [];
        return $result;
    }

    /**
     * Load a renter.
     *
     * @param User $user The renter.
     * @return array All the render variables.
     */
    public function loadRenter(User $user) {
        $result = [];
        return $result;
    }

    /**
     * Load sender.
     *
     * @param User $user The sender.
     * @return array All the render variables.
     */
    public function loadSender(User $user) {
        $result = [];
        return $result;
    }

    /**
     * Load retriever.
     *
     * @param User $user The retriever.
     * @return array All the render variables.
     */
    public function loadRetriever(User $user) {
        $result = [];
        return $result;
    }

}