<?php
/**
 *
 * 'booking' => $booking,
 * 'itemName' => $booking->item->name,
 * 'profileName' => $booking->item->owner->profile->first_name,
 * 'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
 * 'message' => $booking->conversation->messages[0], // there should only be one message
 * 'payout' => $booking->amount_payout . ' DKK',
 * 'dayPrice' => ($booking->amount_item / $numberOfDays) . ' DKK',
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * ],
 * 'urls' => [
 * 'response' => Url::to('@web/booking/' . $booking->id . '/request', true),
 * 'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
 * ]
 */

?>

<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0" st-sortable="1-image+text-column">
    <tbody>
    <tr>
        <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
                   hasbackground="true">
                <tbody>
                <tr>
                    <td width="100%">
                        <table bgcolor="#EEEEEE" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center"
                                           class="devicewidthinner">
                                        <tbody>


                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA"
                                                   style="text-align: left; font-size: 20px;font-weight: 700;">
                                                    Hej <?= $profileName ?>
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    <?= $renterName ?> har sendt dig en henvendelse om <?= $itemName ?>.
                                                    Svare, forhåndsgodkend eller afvis inden <span
                                                        style="font-weight: 600"><?= $startDate ?></span>
                                                    den <span style="font-weight: 600"><?= $endDate ?></span>. Baseret
                                                    på din pris på <span
                                                        style="font-weight: 600"><?= $dayPrice ?></span> pr.
                                                    Døgn samt de tilhørende gebyrer er din potentielle udbetaling for
                                                    denne reservation <span
                                                        style="font-weight: 600"><?= $payout ?></span>.
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #000000; text-align:left; line-height: 24px;"
                                                class="padding-right15">

                                                <p class="BrdtekstA">
                                                <table class="button">
                                                    <tr>
                                                        <td>
                                                            <a href="<?= $urls['response'] ?>">Forhåndsgodkend /
                                                                Afvis</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #000000; text-align:left; line-height: 24px;"
                                                class="padding-right15">
                                                <p class="BrdtekstA">
                                                <table class="button">
                                                    <tr>
                                                        <td>
                                                            <a href="<?= $urls['chat'] ?>">Svar</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </p>
                                            </td>
                                        </tr>
                                        <!-- end of content -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>


<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0" st-sortable="1-image+text-column">
    <tbody>
    <tr>
        <td height="30" style="font-size:30px; line-height:30px; mso-line-height-rule: exactly;">
        </td>
    </tr>
    <tr>
        <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
                   hasbackground="true">
                <tbody>
                <tr>
                    <td width="100%">
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center"
                                           class="devicewidthinner">
                                        <tbody>

                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px;">
                                                <p class="BrdtekstA" style="text-align: left;">
                                                    <?= $message ?>
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <!-- end of content -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
