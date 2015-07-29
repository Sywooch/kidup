<?php

/**
 * 'params' => [
 * 'itemName' => $booking->item->name,
 * 'profileName' => $booking->renter->profile->first_name,
 * 'ownerName' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
 * 'payinAmount' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * ],
 * 'urls' => [
 * 'chat' => Url::to('@web/messages/' . $booking->id . '/conversation', true),
 * 'booking' => Url::to('@web/booking/' . $booking->id, true),
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
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA"
                                                   style="text-align: left; font-size: 20px;font-weight: 700;">
                                                    Hej <?= $profileName ?>
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
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Du har nu tilkendegivet overfor <?= $ownerName ?>, at du gerne vil
                                                    leje hendes <?= $itemName ?> i perioden <?= $startDate ?>
                                                    - <?= $endDate ?>, og vi har nu sendt din anmodning til
                                                    udlejeren <?= $ownerName ?>
                                                    <a href="<?= $urls['booking'] ?>">her</a>. Har du rettelser til din
                                                    booking anmodning, kan du stadig nå at lave det om her. Har du
                                                    spørgsmål til udlejeren kan du chatte med <?= $ownerName ?> <a
                                                        href="<?= $urls['chat'] ?>">her</a>.
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
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Vi vil gøre dig opmærksom på, at dette ikke er en
                                                    bekræftelsesmail. <?= $ownerName ?> har nu 48 timer til at
                                                    accepterer din forespørgsel og dele sin <?= $itemName ?>.
                                                </p>
                                            </td>
                                        </tr>
                                        <!-- Spacing -->
                                        <tr>
                                            <td width="150%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Vi har autoriseret betalingen, så skulle det mod forventning ikke
                                                    blive accepteret, bliver de <?= $payinAmount ?> ikke trukket.
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- Spacing -->
                                        <tr>
                                            <td width="150%" height="15"
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
