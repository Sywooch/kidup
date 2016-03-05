<?php
namespace notification\components\renderer;

use booking\models\payout\Payout;
use notification\components\Renderer;
use user\models\User;

class UserRenderer
{

    private function loadProfileUrl(User $user) {
        return [
            'profile_url' => 'kidup:///'
        ];
    }

    /**
     * Load a reviewer.
     *
     * @param User $user The reviewer.
     * @return array All the render variables.
     */
    public function loadReviewer(User $user) {
        $result = [];
        return $result;
    }

    /**
     * Load a reviewed user (or user to be reviewed).
     *
     * @param User $user The reviewed user.
     * @return array All the render variables.
     */
    public function loadReviewed(User $user) {
        $result = [];
        return $result;
    }

    /**
     * Load a user.
     *
     * @param User $user The user.
     * @return array All the render variables.
     */
    public function loadUser(User $user) {
        $result = [];
        $result = array_merge($result, $this->loadProfileUrl($user));
        $result = array_merge($result, $this->loadReceiver($user));
        return $result;
    }

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
     * Load receiver.
     *
     * @param User $user The retriever.
     * @return array All the render variables.
     */
    public function loadReceiver(User $user) {
        $result = [];
        $result['receiver_email'] = $user->email;
        return $result;
    }

}