<?php

namespace message\models\conversation;

use booking\models\booking\Booking;
use message\models\message\Message;
use message\models\message\MessageFactory;
use user\models\User;
use Yii;

/**
 * This is the model class for table "conversation".
 */
class ConversationFactory
{
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
            $conv = new Conversation();
            $conv->setAttributes([
                'initiater_user_id' => $this->getKidUpUserId(),
                'target_user_id' => $user->id,
                'title' => \Yii::t('app.conversation.from_kidup.title', 'KidUp'),
                'booking_id' => null
            ]);
            $conv->save();
        }
        return $conv;
    }

    /**
     * Creates a new conversation for a booking
     * @param Booking $booking
     * @param $message
     * @return Message
     * @throws ConversationException
     * @throws \message\models\message\MessageException
     */
    public function createBookingConversation(Booking $booking, $message){
        $c = new Conversation();
        $c->initiater_user_id = $booking->renter_id;
        $c->target_user_id = $booking->item->owner_id;
        $c->title = $booking->item->name;
        $c->booking_id = $booking->id;
        $c->created_at = time();
        if (!$c->save()) {
            throw new ConversationException('Conversation couldn\'t be saved' . Json::encode($c->getErrors()));
        }

        if ($message == '' || $message == null) {
            $message = Message::automatedMessageWrapper(\Yii::t('booking.create.automated_new_message',
                '{renter_name} made a rent request for {item_name}', [
                    'renter_name' => \Yii::$app->user->identity->profile->getName(),
                    'item_name' => !empty($booking->item->name) ? $booking->item->name : $booking->item_id
                ]));
        }
        return (new MessageFactory())->addMessageToConversation($message, $c, $booking->renter);
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
