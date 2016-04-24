<?php
namespace notification\components;

use booking\models\booking\Booking;
use message\models\message\Message;
use user\models\user\User;

class NotificationImplementationError extends \app\extended\base\Exception
{
}

;

class NotificationDistributer
{
    private $userAllowsPush = false;

    /**
     * Construct a notification distributor for a certain user id
     * NotificationDistributer constructor.
     * @param $userId
     * @param bool $viewOnly
     * @throws \yii\web\NotFoundHttpException
     */
    public function __construct($userId, $viewOnly=false)
    {
        $user = User::findOneOr404($userId);
        $this->viewOnly = $viewOnly;
        $this->userAllowsPush = $user->getUserAcceptsPushNotifications();
    }

    private function newRenderer($template)
    {
        $isMailTemplate = is_file(\Yii::$aliases['@notification-mail'] . '/' . $template . ".twig");
        $isPushTemplate = is_file(\Yii::$aliases['@notification-push'] . '/' . $template . ".twig");
        if (!$isMailTemplate && !$isPushTemplate) {
            throw new NotificationImplementationError("Template not found");
        }
        if ($this->userAllowsPush) {
            if ($isMailTemplate && $isPushTemplate) {
                return [new MailRenderer($template), new PushRenderer($template)];
            } else {
                if ($isMailTemplate && !$isPushTemplate) {
                    return [new MailRenderer($template)];
                } else {
                    if (!$isMailTemplate && $isPushTemplate) {
                        return [new PushRenderer($template)];
                    } else {
                        if (!$isMailTemplate && !$isPushTemplate) {
                            // Too bad
                            return [];
                        }
                    }
                }
            }
        } else {
            // User does not allow push
            if ($isMailTemplate) {
                // It is a mail template
                return [new MailRenderer($template)];
            } else {
                // Too bad
                return [];
            }
        }
    }

    private function load($templateName, $contents)
    {
        $renderers = $this->newRenderer($templateName);
        if (count($renderers) == 0) {
            $isMailTemplate = is_file(\Yii::$aliases['@notification-mail'] . '/' . $templateName . ".twig");
            $isPushTemplate = is_file(\Yii::$aliases['@notification-push'] . '/' . $templateName . ".twig");
            \Yii::error(json_encode([
                'message' => 'No renderers retrieved.',
                'template' => $templateName,
                'contents' => $contents,
                'userAllowsPush' => $this->userAllowsPush,
                'isMailTemplate' => $isMailTemplate,
                'isPushTemplate' => $isPushTemplate
            ]), 'notifications');
            return false;
        }
        $success = true;
        foreach ($renderers as $renderer) {
            $renderer->setVariables($contents);
            foreach ($contents as $content => $value) {
                if ($value === null) {
                    continue;
                }
                switch ($content) {
                    case "booking":
                        $renderer->loadBooking($value);
                        break;
                    case "user":
                        $renderer->loadUser($value);
                        break;
                    case "message":
                        $renderer->loadMessage($value);
                        break;
                    case "app_state":
                        $renderer->setVariables(['app_state' => $value]);
                }
            }
            
            if ($this->viewOnly) {
                echo $renderer->render();
            }

            // Use the appropriate sender to send the rendering
            if (!$this->viewOnly) {
                if ($renderer->type == 'mail') {
                    $success = $success && MailSender::send($renderer, $this->viewOnly);
                } else {
                    $success = $success && PushSender::send($renderer, $this->viewOnly);
                }
            }
        }
        return $success;
    }

    public function bookingConfirmedOwner(Booking $booking)
    {
        return $this->load("booking_confirmed_owner", [
            'booking' => $booking,
            'user' => $booking->item->owner,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function bookingConfirmedRenter(Booking $booking)
    {
        return $this->load("booking_confirmed_renter", [
            'booking' => $booking,
            'user' => $booking->renter,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function bookingDeclinedRenter(Booking $booking)
    {
        return $this->load("booking_declined_renter", [
            'booking' => $booking,
            'user' => $booking->renter,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function bookingPayoutOwner(Booking $booking)
    {
        return $this->load("booking_payout_owner", [
            'booking' => $booking,
            'user' => $booking->item->owner,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function bookingRequestOwner(Booking $booking)
    {
        return $this->load("booking_request_owner", [
            'booking' => $booking,
            'user' => $booking->item->owner,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function bookingStartOwner(Booking $booking)
    {
        return $this->load("booking_start_owner", [
            'booking' => $booking,
            'user' => $booking->item->owner,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function bookingStartRenter(Booking $booking)
    {
        return $this->load("booking_start_renter", [
            'booking' => $booking,
            'user' => $booking->renter,
            'app_state' => [
                'state' => 'app.booking-view',
                'params' => [
                    'id' => $booking->id
                ]
            ]
        ]);
    }

    public function userReconfirm(User $user)
    {
        return $this->load("user_reconfirm", [
            'user' => $user
        ]);
    }

    public function userRecovery(User $user, $url)
    {
        return $this->load("user_recovery", [
            'user' => $user,
            'url' => $url
        ]);
    }

    public function userWelcome(User $user)
    {
        return $this->load("user_welcome", [
            'user' => $user
        ]);
    }

    public function conversationMessageReceived(Message $message)
    {
        return $this->load("conversation_message_received", [
            'message' => $message,
            'user' => $message->senderUser,
            'app_state' => [
                'state' => 'app.chat-conversation',
                'params' => [
                    'chatId' => $message->conversation_id
                ]
            ]
        ]);
    }
}