<?php
/**
 * 'params' => [
 * 'bookingId' => $booking->id,
 * 'profileName' => $booking->renter->profile->first_name. ' ' . $booking->item->owner->profile->last_name,
 * 'bookingLocation' => $address,
 * 'itemName' => $booking->item->name,
 * 'rentingDays' => $numberOfDays,
 * 'payinDate' => Carbon::createFromTimestamp($booking->payin->updated_at)->toFormattedDateString(),
 * 'amountPerDay' => $booking->amount_item / $numberOfDays . ' DKK',
 * 'amountItem' => $booking->amount_item . ' DKK',
 * 'amountServiceFee' => $booking->amount_payin - $booking->amount_item . ' DKK',
 * 'amountTotal' => $booking->amount_payin . ' DKK',
 * 'amountBalance' => '0 DKK',
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * ],
 * 'urls' => [
 * 'viewReceipt' => Url::to('@web/booking/' . $booking->id . '/receipt', true),
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
                                                   style="text-align: left; font-size: 14px;font-weight: 700;">
                                                    Kundekvittering
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
                                                    Bekæftelseskode: <a href="<?= $urls['viewReceipt'] ?> ">
                                                        <?= $bookingId ?>
                                                    </a>
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    <?= $nowDate ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="20"
                                                style="font-size:20px; line-height:20px; mso-line-height-rule: exactly;">
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
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    Lejer
                                                </p>


                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $profileName ?>
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    Udlejers adresse
                                                </p>


                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $bookingLocation ?>
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    Længe på lejeperiode
                                                </p>


                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $rentingDays ?> døgn
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
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0" st-sortable="1-image+text-column">
    <tbody>
    <tr>
        <td height="20" style="font-size:20px; line-height:20px; mso-line-height-rule: exactly;">
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
                               class="devicewidth" style="border: 1px solid #999999;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                   style="text-align: center; font-size: 16px;">
                                                    Afhentnings tidspunkt
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
                                                    Tilbageleverings tidspunkt
                                                </p>

                                                <p class="BrdtekstA"
                                                   style="text-align: center; font-size: 16px;font-weight: 700;">
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
                        <table bgcolor="#EEEEEE" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="20"
                                    style="font-size:20px; line-height:20px; mso-line-height-rule: exactly;">
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
                                                   style="text-align: left; font-size: 16px;font-weight: 700;">
                                                    Betalingsoplysninger
                                                </p>
                                            </td>
                                        </tr>
                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="5"
                                                style="font-size:5px; line-height:5px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Betaling modtaget tor, <?= $payinDate ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="20"
                                                style="font-size:20px; line-height:20px; mso-line-height-rule: exactly;">
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
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    <?= $amountPerDay ?> * <?= $rentingDays ?> døgn
                                                </p>
                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $profileName ?>
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    KidUp servicegebyr (inkl. moms)
                                                </p>
                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $amountServiceFee ?>
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999; border-bottom: none;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    Total
                                                </p>


                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $amountTotal ?>
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
                        <table bgcolor="#FFFFFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth" style="border: 1px solid #999999;">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td height="2" style="font-size:2px; line-height:2px; mso-line-height-rule: exactly;">
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
                                                    Balance
                                                </p>


                                            </td>

                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; width: 100px"
                                                width="100">
                                                <p class="BrdtekstA"
                                                   style="text-align: right; font-size: 16px;">
                                                    <?= $amountBalance ?>
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
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>