<?php
/**
 * 'params' => [
 * 'profileName' => $booking->item->owner->profile->first_name,
 * 'itemName' => $booking->item->name,
 * 'amountServiceFee' => $booking->amount_item - $booking->amount_payout . ' DKK',
 * 'amountTotal' => $booking->amount_payout . ' DKK',
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
                                                   style="text-align: left; font-size: 20px;font-weight: 700;">
                                                    Hej <?= $profileName ?>
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Vi har sendt dig en udbetaling på <?= $amountTotal ?> via
                                                    bankoverførsel.
                                                    Denne udbetaling skulle være på din konto inden for 5 dage, når
                                                    weekender
                                                    og helligdage medregnes.
                                                    <a href="<?= $urls['viewReceipt'] ?>">
                                                        receipt
                                                    </a>
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
