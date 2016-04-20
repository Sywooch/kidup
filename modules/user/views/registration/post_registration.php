<?php

use app\components\web\ViewHelper;
use images\components\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \user\models\user\User $user
 * @var \user\Module $module
 */

$this->title = ViewHelper::getPageTitle(\Yii::t('user.post_registration.title', 'Complete your registration'));
$this->assetPackage = \app\assets\Package::USER;

?>
<section class="section container">
    <div id="login" class="row">
        <br/><br/>

        <div class="col-md-6 col-md-offset-3 card col-sm-8 col-sm-offset-2">
            <br/><br/>

            <div class="text-center">
                <?= ImageHelper::img('kidup/logo/horizontal.png', ['q' => 90, 'h' => 60]) ?>
                <h4 class="modal-title">
                    <?= Yii::t("user.post_registration.sub_header_who_are_you", "We'd like to get to know you!") ?>
                </h4>
            </div>
            <br/>

            <?php $form = ActiveForm::begin([
                'id' => 'registration-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'options' => ['class' => 'form-vertical'],
                'fieldConfig' => [
                ],
            ]); ?>

            <div class="row register-name-age">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'firstName')->textInput([
                                'placeholder' => \Yii::t('user.post_registration.first_name_placeholder', 'First Name'),
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'lastName')->textInput([
                                'placeholder' => \Yii::t('user.post_registration.last_name_placeholder', 'Last Name'),
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'description')->textarea([
                            'rows' => 5,
                            'placeholder' => \Yii::t('user.post_registration.description_placeholder',
                                'What is interesting for other users to know? Do you have any kids? How old are they?')
                        ]); ?>
                    </div>
                    <?= $form->field($model, 'language')->widget(\kartik\select2\Select2::className(), [
                        'data' => \app\components\view\SelectData::languages(),
                        'options' => ['placeholder' => \Yii::t('user.post_registration.select_language_propdown_plceholder',
                            'Select your preferred language')],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]); ?>
                </div>
            </div>
            <?= Html::submitButton(\Yii::t('user.post_registration.complete_button', 'Complete'),
                ['class' => "btn btn-danger btn-fill btn-block"]) ?>
            <?php ActiveForm::end(); ?>
            <a href="<?= Url::to('@web/home') ?>" class="pull-right">
                <br/>
                <i><?= Yii::t("user.post_registration.skip_post_registation_link", "Or skip") ?></i>
                <br/>
                <br/>
            </a>
        </div>
    </div>
</section>

