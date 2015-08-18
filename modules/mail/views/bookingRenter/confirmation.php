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
 * 'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
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
                                                    Vi er glade for at kunne meddele dig at du har lejet
                                                    ”<?= $itemName ?>”.
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    ”<?= $ownerName ?>” har bekræftet din anmodning om ”<?= $itemName ?>
                                                    ” se detaljerne for
                                                    din leje og kontakt din
                                                    <a href="<?= $urls['chat'] ?> ">vært</a> for at koordinere
                                                    udvekslingstidspunkt og udvekslingssted.
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    <?= Yii::t("mail", "You can view the details of the booking {0}", [
                                                        \yii\helpers\Html::a(\Yii::t('mail', 'here'), $urls['booking'])
                                                    ]) ?>.
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
                                                <p class="BrdtekstA" style="text-align: left; font-weight: 700">
                                                    Information omkring udstyret:
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Du har lånt en ”<?= $itemName ?>”.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left; font-weight: 700">
                                                    Information omkring længden af lånet:
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Du har lånt ”<?= $itemName ?>” fra d. <?= $startDate ?>
                                                    til <?= $endDate ?>.
                                                    Ønsker du i slutningen af lånet at beholde ”<?= $itemName ?>”
                                                    yderligere, så kan du
                                                    kontakte <?= $ownerName ?>.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:15px; line-height:15px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px; ">
                                                <p class="BrdtekstA" style="text-align: left; font-weight: 700">
                                                    Information omkring udlejers “krav”:
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Husk, at vi altid passer på hinandens ting, og behandler dem som var
                                                    de vore egne.
                                                    Det indebærer selvfølgelig en god rengøring af Barnevognen inden den
                                                    leveres tilbage til
                                                    <?= $ownerName ?>, det samme gør Britta nemlig inden du får den i
                                                    hænderne første gang.
                                                    Hvis noget skulle gå i stykker, så kontakt Britta og få det snakket
                                                    igennem, kan i ikke komme
                                                    til en løsning er vi selvfølgelig behjælpelige og sikker på, at vi
                                                    nok skal finde frem
                                                    til et resultat i fællesskab!
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
