<?php

/**
 * 'params' => [
 * 'itemName' => $booking->item->name,
 * 'profileName' => $booking->renter->profile->first_name,
 * 'ownerName' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
 * 'ownerLocation' => $address,
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * 'phoneNumber' => $booking->item->owner->profile->phone_country . ' ' . $booking->item->owner->profile->phone_number
 * ],
 * 'urls' => [
 * 'booking' => Url::to('@web/booking/' . $booking->id, true),
 * 'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
 * 'help' => Url::to('@web/contact', true),
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
                                                    Dette er en påmindelse om, at du har en kommende reservation. Vi
                                                    anbefaler, at du verificerer den eksakte adresse og telefonnummer,
                                                    direkte med værten.
                                                </p>
                                            </td>
                                        </tr>
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
                               class="devicewidth" style="border: 1px solid #999999;">
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
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: center; font-size: 18px;">
                                                    Udlejning starter
                                                </p>

                                                <p class="BrdtekstA"
                                                   style="text-align: center; font-size: 18px;font-weight: 700;">
                                                    <?= $startDate ?>
                                                </p>
                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: center; font-size: 18px;">
                                                    Udlejning slutter
                                                </p>

                                                <p class="BrdtekstA"
                                                   style="text-align: center; font-size: 18px;font-weight: 700;">
                                                    <?= $endDate ?>
                                                </p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
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
                        <table bgcolor="#EEEEEE" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="15"
                                    style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
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
                                                   style="text-align: left; ">
                                                    Udstyrets detaljer
                                                </p>
                                            </td>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right;">
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
                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999;">
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
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 50%"
                                                width="50%">
                                                <p class="BrdtekstA"
                                                   style="text-align: left; ">
                                                    Udlejer
                                                </p>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 50%"
                                                width="50">
                                                <p class="BrdtekstA"
                                                   style="text-align: right;">
                                                    <a href="<?= $urls['chat'] ?>">
                                                        <?= $ownerName ?>
                                                    </a>
                                                </p>

                                                <p class="BrdtekstA"
                                                   style="text-align: right;">
                                                    <?= $ownerLocation ?>
                                                </p>

                                                <p class="BrdtekstA"
                                                   style="text-align: right;">
                                                    <?= $phoneNumber ?>
                                                </p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>
                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
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

                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Før du afhenter, er det en god ide at gennemlæse tjeklisten for
                                                    udstyret:
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    1. <a href="<?= $urls['booking'] ?>">Udskriv og medbring din booking
                                                        bekræftelse</a>, herunder værtens
                                                    adresse og telefonnummer.
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    2. <a href="<?= $urls['chat'] ?>">Kontakt værten</a> før udveksling
                                                    af udstyret for at få
                                                    kørselsvejledninger, bekræfte dit afhentningstidspunkt, og
                                                    verificere værtens kontaktoplysninger.
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    3. Hvis du på noget tidspunkt har brug for assistance under din
                                                    lejeperiode, kan du besøge vores <a href="<?= $urls['help'] ?>">hjælpecenter</a>.

                                                </p>
                                            </td>
                                        </tr>
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