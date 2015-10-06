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
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #000000; text-align:left; line-height: 24px;">
                                                <p class="BrdtekstA" style="text-align: center;">
                                                    Hej <?= $profileName ?>
                                                </p>

                                                <p class="BrdtekstA" style="text-align: left;">
                                                    Du har sendt os en forespørgsel på at gendanne dit kodeord. Hvis dette har været en fejl skal du blot se bort fra denne mail.
                                                    Hvis du ønsker at ændre dit kodeord skal du klikke på nedenstående knap:
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- Spacing -->
                                        <tr>
                                            <td width="100%" height="15"
                                                style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #000000; text-align:left; line-height: 24px;"
                                                class="padding-right15">

                                                <p class="BrdtekstA">
                                                <table class="button">
                                                    <tr>
                                                        <td>
                                                            <a href="<?= $urls['recovery'] ?>">
                                                                <?= Yii::t("mail.recovery.link", "Password recovery") ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </p>
                                            </td>
                                        </tr>
                                        <!-- end of content -->

                                        <!-- /Spacing --><!-- content -->
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
