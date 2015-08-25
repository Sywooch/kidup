<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use kartik\sidenav\SideNav;
use kartik\icons\Icon;
?>

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
            'label' => Icon::show('user') . Yii::t('user', 'Profile'),
            'url' => ['/user/settings/profile'] // use brackets instead of @web to let the menu automatically add active class
        ],
        [
            'label' => Icon::show('lock') . Yii::t('user', 'Trust and Verification'),
            'url' => ['/user/settings/verification']
        ],
        [
            'label' => Icon::show('gears') . Yii::t('user', 'User Settings'),
            'url' => ['/user/settings/account']
        ],
        [
            'label' => Icon::show('bank') . Yii::t('user', 'Payout Preferences'),
            'url' => ['/user/settings/payout-preference']
        ],
        [
            'label' => Icon::show('map-marker') . Yii::t('user', 'Billing Address'),
            'url' => ['/user/settings/location']
        ],
    ],
]); ?>