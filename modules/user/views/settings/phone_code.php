<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var \user\models\profile\Profile $profile
 */
$this->title = ucfirst(\Yii::t('user.settings.phone_confirm.title', 'Confirm your telephone')) . ' - ' . Yii::$app->name;
?>
<div class="row" style="margin-top: 100px">
    <div class="col-md-4 col-md-offset-4">
        <div class="card" style="padding:20px;">
            <h3><?= Yii::t("user.settings.phone_confirm.header", "Confirm phone") ?></h3>

            <form action="<?= Url::to(['phonecode']) ?>" method="get">
                <?= \Yii::t('user.settings.phone_confirm.enter_received_code', 'Please enter the code you received on {0} by text.',
                    [$profile->getPhoneNumber()]) ?>
                <br/><br/>

                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="code"
                               placeholder="<?= Yii::t("user.settings.phone_confirm.received_kidup_code", "Kidup Code...") ?>"/>
                    </div>
                    <div class="col-md-4">
                        <?= Html::submitButton(\Yii::t('user.settings.phone_confirm.verify_button', 'Verify'),
                            ['class' => 'btn btn-primary btn-fill btn-lg']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
