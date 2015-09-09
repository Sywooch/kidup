<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var \app\modules\item\forms\CreateBooking $model
 */

\yii\jui\JuiAsset::register($this);

?>
<?php Pjax::begin(); ?>
    <div class="col-md-3 card" id="booking-widget">
        <h3 style="margin-top:0">
        <span class="pull-left">
            &euro; 250
        </span>
        <span class="pull-right">
            per week
        </span>
        </h3>

        <?php

        $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'method' => 'post',
            'options' => ['name' => 'data-pjax', 'data-pjax' => '#booking-widget']
        ]); ?>
        <div class="row">
            <div class="col-md-6">
                <?= Yii::t("item", "Rent starting") ?>
                <br>
                <?php
                // pass available dates to javascript
                $this->registerJs(new JsExpression('window.datesRents = ' . json_encode($model->periods) . ';'));
                echo $form->field($model, 'dateFrom')
                    ->label(false)
                    ->widget(DatePicker::className(), [
                        // these functions are defined in ./assets/booking_widget.js
                        'clientOptions' => [
                            'beforeShowDay' => new JsExpression('widget.dateFrom.beforeShowDay'),
                            'onSelect' => new JsExpression('widget.dateFrom.onSelect')
                        ],
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => \Yii::t('item', 'dd-mm-yyyy')
                        ],
                        'language' => Yii::$app->language == 'en' ? 'en-NZ' : Yii::$app->language,
                        'dateFormat' => 'dd-MM-yyyy',

                    ])
                ?>
            </div>
            <div class="col-md-6">
                <?= Yii::t("item", "Rent ending") ?>
                <br>
                <?php
                echo $form->field($model, 'dateTo',
                    [
                        'options' => ['class' => 'drp-container form-group'],
                    ])->label(false)->widget(DatePicker::className(),
                    [
                        'clientOptions' => [
                            'beforeShowDay' => new JsExpression('widget.dateTo.beforeShowDay')
                        ],
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => \Yii::t('item', 'dd-mm-yyyy')
                        ],
                        'dateFormat' => 'dd-MM-yyyy',
                        'language' => Yii::$app->language == 'en' ? 'en-NZ' : Yii::$app->language,
                    ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= Html::submitButton(\Yii::t('item', 'Request to Book'),
                    [
                        'class' => 'btn btn-danger btn-fill x',
                        'style' => 'width:100%',
                        'id' => 'request-booking-btn'
                    ]); ?>
            </div>
        </div>
        <?php
        ActiveForm::end();

        ?>
    </div>

<?php Pjax::end(); ?>