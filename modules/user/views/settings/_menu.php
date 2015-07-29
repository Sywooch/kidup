<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\widgets\Menu;

/** @var app\modules\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
function buildMenuItem($icon, $text){
    return '<div class="row">
    <div class="col-md-1"><i class="fa fa-'. $icon.'"></i></div>
    <div class="col-md-9">'.$text.'</div>
</div>';
}
?>
<?= Menu::widget([
    'options' => [
        'class' => 'nav nav-stacked nav-icons',
//        'style' => 'margin-top:35px'
    ],
    'items' => [
        [
            'label' => buildMenuItem('user', Yii::t('user', 'Profile')),
            'url' => ['/user/settings/profile']
        ],
        [
            'label' => buildMenuItem('gears', Yii::t('user', 'User Settings')),
            'url' => ['/user/settings/account']
        ],
        [
            'label' => buildMenuItem('map-marker', Yii::t('user', 'Location')),
            'url' => ['/user/settings/location']
        ],
        [
            'label' => buildMenuItem('bank', Yii::t('user', 'Payout Preferences')),
            'url' => ['/user/settings/payout-preference']
        ],
        [
            'label' => buildMenuItem('lock', Yii::t('user', 'Trust and Verification')),
            'url' => ['/user/settings/verification'],
            'visible' => $networksVisible
        ],
    ],
    'encodeLabels' => false
]) ?>
