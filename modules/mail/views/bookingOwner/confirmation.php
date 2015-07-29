<?php
/**
 * 'params' => [
 * 'itemName' => $booking->item->name,
 * 'profileName' => $booking->item->owner->profile->first_name,
 * 'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
 * 'renterPhone' => $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number,
 * 'payinAmount' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
 * 'payinId' => $booking->payin->id,
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * 'numberOfDys' => $numberOfDays,
 * 'amountPerDay' => $booking->amount_item / $numberOfDays . ' DKK',
 * 'amountItem' => $booking->amount_item . ' DKK',
 * 'amountServiceFee' => $booking->amount_item - $booking->amount_payout . ' DKK',
 * 'amountTotal' => $booking->amount_payout . ' DKK',
 * ],
 * 'urls' => [
 * 'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
 * 'booking' => Url::to('@web/booking/' . $booking->id, true),
 * 'help' => Url::to('@web/help', true),
 * 'contact' => Url::to('@web/contact', true),
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
                        <table bgcolor="#EEEEEE" width="560" cellpadding="0" cellspacing="0" border="0"
                               align="center"
                               class="devicewidth">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="20"
                                    style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
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
                                                    Vi er glade for at kunne fortælle dig, at en forælder har
                                                    booket <?= $itemName ?>!
                                                    For at gøre udvekslingen af udstyret problemfri foreslår vi, at
                                                    du fortsætter samtalen
                                                    med <?= $renterName ?> Via KidUp’s meddelelsessystem for at
                                                    bekræfte jeres
                                                    udvekslingstidspunkt, stille eventuelle spørgsmål og hjælpe dem
                                                    med at finde ud af,
                                                    hvordan man nemmest kommer frem til din adresse.
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
                                                    Tak fordi du deler dit udstyr via KidUp
                                                </p>
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
        </td>
    </tr>
    </tbody>
</table>
<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0" st-sortable="1-image+text-column">
    <tbody>
    <tr>
        <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
                   hasbackground="true">
                <tbody>
                <tr>
                    <td width="100%">
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0"
                               align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2"
                                    style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center"
                                           class="devicewidthinner">
                                        <tbody>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: left; font-size: 18px;">
                                                    Udstyrsudleje
                                                </p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td height="10"
                                    style="font-size:10px; line-height:10px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0"
                               align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2"
                                    style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center"
                                           class="devicewidthinner">
                                        <tbody>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: left; font-size: 16px;">
                                                    Bekræftelseskode: <?= $payinId ?>
                                                </p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td height="10"
                                    style="font-size:10px; line-height:10px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0"
                               align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2"
                                    style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center"
                                           class="devicewidthinner">
                                        <tbody>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: left; font-size: 16px;">
                                                    Udstyr
                                                </p>


                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $itemName ?>
                                                </p>

                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td height="10"
                                    style="font-size:10px; line-height:10px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->

                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td width="100%">
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0"
                               align="center"
                               class="devicewidth" style="border: 1px solid #999999;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2"
                                    style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                    width="100">
                                    <p class="BrdtekstA"
                                       style="text-align: center; font-size: 16px;">
                                        Udleje starter:
                                    </p>

                                    <p class="BrdtekstA"
                                       style="text-align: center; font-size: 16px;font-weight: 700;">
                                        <?= $startDate ?>
                                    </p>
                                </td>

                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                    width="100">
                                    <p class="BrdtekstA"
                                       style="text-align: center; font-size: 16px;">
                                        Udleje slutter:
                                    </p>

                                    <p class="BrdtekstA"
                                       style="text-align: center; font-size: 16px;font-weight: 700;">
                                        <?= $endDate ?>
                                    </p>
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td height="10"
                                    style="font-size:10px; line-height:10px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->

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
        <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
                   hasbackground="true">
                <tbody>
                <tr>
                    <td width="100%">
                        <table bgcolor="#EEEEEE" width="560" cellpadding="0" cellspacing="0" border="0"
                               align="center"
                               class="devicewidth">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="20"
                                    style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center"
                                           class="devicewidthinner">
                                        <tbody>
                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <table class="button">
                                            <tr>
                                                <td>
                                                    <a href="<?= $urls['booking'] ?>">Se udstyrudleje</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
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
        </td>
    </tr>
    </tbody>
</table>
