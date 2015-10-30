<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use kartik\sidenav\SideNav;
use kartik\icons\Icon;
?>

<!-- display a warning for mobile visitors -->
<div class="row visible-xs">
    <br />
    <div class="col-xs-12">
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <?= Yii::t("user.settings.mobile_warning", "This page is not optimized for mobile devices. Currently we are working on a mobile app where it is easier to change your settings.") ?>
        </div>
    </div>
</div>

<?= SideNav::widget([
    'type' => SideNav::TYPE_PRIMARY,
    'heading' => false,
    'encodeLabels' => false,
    'options' => [
        'class' => 'nav nav-stacked nav-icons',
//        'style' => 'margin-top:35px'
    ],
    'items' => [
        [
            'label' => Icon::show('user') . Yii::t('user.settings.menu.profile', 'Profile'),
            'url' => ['/user/settings/profile'] // use brackets instead of @web to let the menu automatically add active class
        ],
        [
            'label' => Icon::show('lock') . Yii::t('user.settings.menu.verification', 'Trust and Verification'),
            'url' => ['/user/settings/verification']
        ],
        [
            'label' => Icon::show('gears') . Yii::t('user.settings.menu.settings', 'User Settings'),
            'url' => ['/user/settings/account']
        ],
        [
            'label' => Icon::show('bank') . Yii::t('user.settings.menu.payout_preferences', 'Payout Preferences'),
            'url' => ['/user/settings/payout-preference']
        ],
        [
            'label' => Icon::show('map-marker') . Yii::t('user.settings.menu.billing_address', 'Billing Address'),
            'url' => ['/user/settings/location']
        ],
    ],
]); ?>