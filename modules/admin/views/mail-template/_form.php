<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var mail\models\base\MailTemplate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mail-template-form">
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);

            echo $form->field($model, 'template')->widget('trntv\aceeditor\AceEditor',
                [
                    'mode' => 'twig', // programing language mode. Default "html"
                    'theme' => 'github' // editor theme. Default "github"
                ])->label(false);
            echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
            ActiveForm::end(); ?>
            <table class="table table-striped">
                <tr>
                    <td><var>{{ line_widget({height:1, color: 'grey'}) | raw }}</var></td>
                </tr>
                <tr>
                    <td><var>{{ button_widget({content: "I'm a button"}) | raw }}</var></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <iframe src="<?= \yii\helpers\Url::to("@web/admin/mail-template/preview?id=" . $model->id, true) ?>"
                    frameborder="1" height="600px" width="100%" id="mailPreview"></iframe>
        </div>
    </div>
</div>
