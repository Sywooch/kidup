<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\images\components\ImageHelper;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

// needed to pass urls variable to the template
if (isset(\Yii::$app->params['tmp_email_params'])) {
    $urls = \Yii::$app->params['tmp_email_params']['urls'];
}

?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

    <!-- Define Charset -->
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>

    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>

    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,600,700,500' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,700,900' rel='stylesheet' type='text/css'>

    <title><?= Html::encode($this->title) ?></title><!-- Responsive Styles and Valid Styles -->
    <?php $this->head() ?>

    <style type="text/css">

        /* Client-specific Styles */
        #outlook a {
            padding: 0;
        }

        /* Force Outlook to provide a "view in browser" menu link. */
        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
        .ExternalClass {
            width: 100%;
        }

        /* Force Hotmail to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
            line-height: 100%;
        }

        /* Force Hotmail to display normal line spacing. */
        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #303030;

            text-decoration: underline;
            text-decoration: underline !important;
        }

        /*STYLES*/
        table[class=full] {
            width: 100%;
            clear: both;
        }

        /*IPAD STYLES*/
        @media only screen and (max-width: 640px) {
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }

            table[class=devicewidth] {
                width: 440px !important;
                text-align: center !important;
            }

            table[class=devicewidthmob] {
                width: 420px !important;
                text-align: center !important;
            }

            table[class=devicewidthinner] {
                width: 420px !important;
                text-align: center !important;
            }

            img[class=banner] {
                width: 440px !important;
                height: 157px !important;
            }

            img[class=col2img] {
                width: 440px !important;
                height: 330px !important;
            }

            table[class="cols3inner"] {
                width: 100px !important;
            }

            table[class="col3img"] {
                width: 131px !important;
            }

            img[class="col3img"] {
                width: 131px !important;
                height: 82px !important;
            }

            table[class='removeMobile'] {
                width: 10px !important;
            }

            img[class="blog"] {
                width: 420px !important;
                height: 162px !important;
            }
        }

        /*IPHONE STYLES*/
        @media only screen and (max-width: 480px) {
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }

            table[class=devicewidth] {
                width: 280px !important;
                text-align: center !important;
            }

            table[class=devicewidthmob] {
                width: 260px !important;
                text-align: center !important;
            }

            table[class=devicewidthinner] {
                width: 260px !important;
                text-align: center !important;
            }

            img[class=banner] {
                width: 280px !important;
                height: 100px !important;
            }

            img[class=col2img] {
                width: 280px !important;
                height: 210px !important;
            }

            table[class="cols3inner"] {
                width: 260px !important;
            }

            img[class="col3img"] {
                width: 280px !important;
                height: 175px !important;
            }

            table[class="col3img"] {
                width: 280px !important;
            }

            img[class="blog"] {
                width: 260px !important;
                height: 100px !important;
            }

            td[class="padding-top-right15"] {
                padding: 15px 15px 0 0 !important;
            }

            td[class="padding-right15"] {
                padding-right: 15px !important;
            }
        }

        /* Buttons */

        table.button,
        table.tiny-button,
        table.small-button,
        table.medium-button,
        table.large-button {
            width: 100%;
            overflow: hidden;
        }

        table.button td,
        table.tiny-button td,
        table.small-button td,
        table.medium-button td,
        table.large-button td {
            display: block;
            width: auto !important;
            text-align: center;
            background: rgb(233, 94, 94);

            color: rgb(255, 255, 255);
            padding: 8px 0;
            -webkit-appearance: none;
            -webkit-user-select: none;
            align-items: flex-start;
        }

        table.tiny-button td {
            padding: 5px 0 4px;
        }

        table.small-button td {
            padding: 8px 0 7px;
        }

        table.medium-button td {
            padding: 12px 0 10px;
        }

        table.large-button td {
            padding: 21px 0 18px;
        }

        table.button td a,
        table.tiny-button td a,
        table.small-button td a,
        table.medium-button td a,
        table.large-button td a {
            font-weight: bold;
            text-decoration: none;
            font-family: Helvetica, Arial, sans-serif;
            color: #ffffff;
            font-size: 16px;
        }

        table.tiny-button td a {
            font-size: 12px;
            font-weight: normal;
        }

        table.small-button td a {
            font-size: 16px;
        }

        table.medium-button td a {
            font-size: 20px;
        }

        table.large-button td a {
            font-size: 24px;
        }

        table.button:hover td,
        table.button:visited td,
        table.button:active td {
            background: #2795b6 !important;
        }

        table.button:hover td a,
        table.button:visited td a,
        table.button:active td a {
            color: #fff !important;
        }

        table.button:hover td,
        table.tiny-button:hover td,
        table.small-button:hover td,
        table.medium-button:hover td,
        table.large-button:hover td {
            background: #2795b6 !important;
        }

        table.button:hover td a,
        table.button:active td a,
        table.button td a:visited,
        table.tiny-button:hover td a,
        table.tiny-button:active td a,
        table.tiny-button td a:visited,
        table.small-button:hover td a,
        table.small-button:active td a,
        table.small-button td a:visited,
        table.medium-button:hover td a,
        table.medium-button:active td a,
        table.medium-button td a:visited,
        table.large-button:hover td a,
        table.large-button:active td a,
        table.large-button td a:visited {
            color: #ffffff !important;
        }

        table.primary td {
            background: #71baaf;
            color: #dddddd;
        }

        table.primary td a {
            color: #dddddd;
        }

        table.primary:hover td {
            background: #5ca196 !important;
            color: #dddddd;
        }

        table.primary:hover td a,
        table.primary td a:visited,
        table.primary:active td a {
            color: #dddddd !important;
        }

        table.default td {
            background: #e9e9e9;
            border-color: #d0d0d0;
            color: #555;
        }

        table.default td a {
            color: #555;
        }

        table.default:hover td {
            background: #d0d0d0 !important;
            color: #555;
        }

        table.default:hover td a,
        table.default td a:visited,
        table.default:active td a {
            color: #555 !important;
        }
    </style>
</head>
<?php $this->beginBody() ?>
<body>
<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0" st-sortable="preheader">
    <tbody>
    <tr>
        <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
                   hasbackground="true">
                <tbody>
                <tr>
                    <td width="100%">
                        <table width="560" cellpadding="0" cellspacing="0" border="0" align="center"
                               class="devicewidth">
                            <tbody>
                            <!-- Spacing -->
                            <tr>
                                <td width="100%" height="10">
                                </td>
                            </tr>
                            <!-- Spacing -->
                            <tr>

                                <td width="150px"
                                    style="font-family: Helvetica, arial, sans-serif; font-size: 10px;color: #303030;text-align:left;"
                                    st-content="viewonline">
                                    <p>
                                        <a href="<?= $urls['mailInBrowser'] ?>">
                                            <?= Yii::t("mail", "Click here to read this mail in your browser") ?>
                                        </a>
                                    </p>
                                </td>
                                <td width="150px"
                                    style="font-family: Helvetica, arial, sans-serif; font-size: 10px;color: #303030;text-align:center;width: 150px"
                                    st-content="viewonline">
                                    <p style="display: block; border-style: none !important; border: 0 !important;">
                                        <img width="70" border="0" style="display: block; width: 100px;"
                                             src="<?= ImageHelper::url('kidup/logo/horizontal.png',
                                                 ['w' => 70]) ?>" alt=""/>
                                    </p>
                                </td>
                                <!-- Spacing -->
                            </tr>
                            <tr>
                                <td width="100%" height="10">
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

<?= $content ?>

<!-- ======= 2 columns tables ======= -->
<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0">

    <tr>
        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">

            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                <tr>
                    <td align="center"
                        style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #303030;text-align:center;"
                        class="text_color">
                        <!-- ======= section subtitle ====== -->

                        <div style="line-height: 24px;">

                            Barnlige hilsner

                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center"
                        style="color: #111212; font-size: 15px; font-family: Lato, Calibri, sans-serif; font-weight: 900; mso-line-height-rule: exactly;"
                        class="title_color">
                        <p
                            style="display: block; border-style: none !important; border: 0 !important;">
                            <img width="100" border="0" style="display: block;"
                                 src="<?= ImageHelper::url('kidup/logo/horizontal.png', ['w' => 100]) ?>"
                                 alt=""/></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>


</table>
<!-- ======= end section ======= -->

<!-- ======= contact section ======= -->
<table width="100%" bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" border="0" st-sortable="footer">

    <tr>
        <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
    </tr>

    <tr>
        <td>
            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590 bg_color">

                <tr>
                    <td>
                        <table border="0" width="240" align="center" cellpadding="0" cellspacing="0"
                               style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                               class="container590">
                            <tr>
                                <td align="center"
                                    style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #303030;text-align:center; font-weight: 700; mso-line-height-rule: exactly; line-height: 24px;"
                                    class="title_color">

                                    <!-- ======= main header ======= -->

                                    <div style="line-height: 24px;">
                                        <?= Yii::t("mail", "KidUp Social") ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td align="center">
                                    <table border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <a href="" style="display: block;">
                                                    <img width="12" border="0" style="display: block; width: auto;"
                                                         src="<?= ImageHelper::url('kidup/email/instagram.png',
                                                             ['q' => 90, 'w' => 12]) ?>" alt=""/></a>
                                            </td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td>
                                                <a href="" style="display: block;">
                                                    <img width="6" height="13" border="0"
                                                         style="display: block; width: auto;"
                                                         src="<?= ImageHelper::url('kidup/email/facebook.png',
                                                             ['q' => 90, 'w' => 6]) ?>"
                                                         alt=""/></a>
                                            </td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td>
                                                <a href="" style="display: block;">
                                                    <img width="14" border="0" style="display: block; width: auto;"
                                                         src="<?= ImageHelper::url('kidup/email/twitter.png',
                                                             ['q' => 90, 'w' => 14]) ?>"
                                                         alt=""/></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td align="center"
                                    style="color: #1a1c20; font-size: 12px; font-family: Helvetica, arial, sans-serif; mso-line-height-rule: exactly; line-height: 25px;"
                                    class="title_color">

                                    <!-- ======= main header ======= -->

                                    <div style="line-height: 25px;">
                                        <a href="<?= $urls['changeSettings'] ?>">
                                            <?= Yii::t("mail", "Change email preferences") ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
    </tr>

</table>
<!-- ======= end section ======= -->

<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>





