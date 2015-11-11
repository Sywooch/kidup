<?php
/** 'params' => [
 * 'itemName' => $booking->item->name,
 * 'profileName' => $booking->item->owner->profile->first_name,
 * 'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
 * 'itemAmount' => round($booking->amount_item),
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * 'phoneNumber' => $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number,
 * 'kidUpEmail' => 'mailto:info@kidup.dk'
 * ],
 * 'urls' => [
 * 'help' => Url::to('@web/contact', true),
 * ]
 * */ ?>

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
                                                    Betaling for leje af <?= $itemName ?> i perioden <?= $startDate ?>
                                                    - <?= $endDate ?> til <?= $renterName ?> mislykkedes og bookingen er
                                                    derfor blevet annulleret. Vi har informeret <?= $renterName ?> om
                                                    denne mislykket betaling da dette kan være en teknisk fejl,
                                                    kan <?= $renterName ?> stadig være interesseret i at
                                                    leje <?= $itemName ?>. Såfremt <?= $renterName ?> stadig er
                                                    interesseret i at leje <?= $itemName ?> vil du modtage en ny booking
                                                    anmodning.
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
                                                    Har du spørgsmål angående betaling kan du læse mere <a
                                                        href="<?= $urls['help'] ?>">her</a>
                                                    eller kontakte os direkte på <a href="<?= $kidUpEmail ?>">mail</a>.
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
