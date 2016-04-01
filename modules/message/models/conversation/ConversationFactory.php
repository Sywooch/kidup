<?php

namespace message\models\conversation;

use app\components\models\FactoryTrait;
use booking\models\booking\Booking;
use message\models\message\Message;
use message\models\message\MessageFactory;
use React\Dns\BadServerException;
use user\models\user\User;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "conversation".
 */
class ConversationFactory extends Conversation
{

    use FactoryTrait;

    /**
     * gets or creates a conversation from kidup to a user
     * @param User $user
     * @return Conversation
     * @throws ConversationException
     */
    public function getOrCreateKidUpConversation(User $user)
    {
        $conv = Conversation::findOne([
            'initiater_user_id' => $this->getKidUpUserId(),
            'target_user_id' => $user->id,
        ]);
        if ($conv == null) {
            $this->setAttributes([
                'initiater_user_id' => $this->getKidUpUserId(),
                'target_user_id' => $user->id,
                'title' => \Yii::t('app.conversation.from_kidup.title', 'KidUp'),
                'booking_id' => null
            ]);
            return $this->create();
        }
        return $conv;
    }

    public function beforeValidate()
    {
        $this->initiater_user_id = \Yii::$app->user->id;

        return parent::beforeValidate();
    }

    public function create(){
        $this->validate();
        $conv = Conversation::find()->where([
            'target_user_id' => $this->target_user_id,
            'initiater_user_id' => $this->initiater_user_id,
            'booking_id' => $this->booking_id
        ])->one();
        if($conv !== null){
            return $conv;
        }
        $this->save();
        return $this;
    }

    /**
     * Creates a new conversation for a booking
     * @param Booking $booking
     * @param $message
     * @return Message
     * @throws ConversationException
     * @throws \message\models\message\MessageException
     */
    public function createFromBooking(Booking $booking){
        $this->setAttributes([
            'initiater_user_id' => $booking->renter_id,
            'target_user_id' => $booking->item->owner_id,
            'title' => $booking->item->name,
            'booking_id' => $booking->id
        ]);
        return $this->create();
    }

    /**
     * Get the user id that is representin kidup
     * @return int
     * @throws ConversationException
     */
    private function getKidUpUserId()
    {
        $id = \Yii::$app->params['kidup-account-id'];
        if (User::find()->where(['id' => $id])->count() == 1) {
            return $id;
        }
        $id = 1;
        if (User::find()->where(['id' => $id])->count() == 1) {
            return $id;
        }
        throw new ConversationException("No default user found to notify user");
    }
}
