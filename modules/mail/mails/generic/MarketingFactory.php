<?php
namespace mail\mails\generic;


/**
 * Recover email factory
 */
class MarketingFactory
{
    public static function create(\user\models\User $user, $templateId)
    {
        $e = new Marketing();

        $e->emailAddress = $user->email;
        $e->templateId = $templateId;
        return $e;
    }
}