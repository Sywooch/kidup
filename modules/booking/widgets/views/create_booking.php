<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

\yii\jui\JuiAsset::register($this);
?>
    <!-- this is the date dropbown field-->
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'method' => 'post'
]); ?>
    <div class="col-sm-2  col-md-offset-1 ">
        <?php
        // pass available dates to javascript
        $this->registerJs(new JsExpression('window.datesRents = ' . json_encode($periods) . ';'));
        echo $form->field($model, 'dateFrom')
            ->label(false)
            ->widget(DatePicker::className(), [
                'clientOptions' => [
                    'beforeShowDay' => new JsExpression(<<<JS
                        function(date) {
                            var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
                            if(date < tomorrow){
                                return [false];
                            }
                            for (var i = 0; i < window.datesRents.length; i++) {
                                var from = new Date(window.datesRents[i][0] * 1000);
                                var to = new Date(window.datesRents[i][1] * 1000);
                                if(date >= from && date <= to){
                                    return [false]
                                }
                            }
                            return [true];
                        }
JS
                    ),
                    'onSelect' => new JsExpression('function(date){
                            window.startDate = date;
                            $("#create-booking-datefrom").datepicker("hide");
                            setTimeout(function(){
                                $("#create-booking-dateto").datepicker("show");
                            },100);
                        }')
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => \Yii::t('item', 'Receive date')
                ],
                'language' => Yii::$app->language == 'en' ? 'en-NZ' : Yii::$app->language,
                'dateFormat' => 'dd-MM-yyyy',

            ])
        ?>
    </div>
    <div class="col-sm-2 ">
        <?php
        echo $form->field($model, 'dateTo',
            [
                'options' => ['class' => 'drp-container form-group'],
            ])->label(false)->widget(DatePicker::className(),
            [
                'clientOptions' => [
                    'beforeShowDay' => new JsExpression(<<<JS
                            function(date) {
                            var startdate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
                            var val = $('#create-booking-datefrom').val().split('-');
                            var pickedDate = new Date(val[2],val[1]-1,val[0]);
                            if(pickedDate > startdate){
                                startdate = pickedDate;
                            }
                            if(date < startdate){
                                return [false];
                            }
                            for (var i = 0; i < window.datesRents.length; i++) {
                                var from = new Date(window.datesRents[i][0] * 1000);
                                var to = new Date(window.datesRents[i][1] * 1000);
                                if(date >= from && date <= to){
                                    return [false]
                                }
                            }
                            return [true];
                            }
JS
                    )
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => \Yii::t('item', 'Return date')
                ],
                'dateFormat' => 'dd-MM-yyyy',
                'language' => Yii::$app->language == 'en' ? 'en - NZ' : Yii::$app->language,
            ])
        ?>
    </div>
    <div class="col-sm-4 col-lg-3">
        <?= Html::submitButton('Start booking!',
            ['class' => 'btn btn-danger btn-fill', 'style' => 'padding-top: 5px']); ?>
    </div>
<?php ActiveForm::end(); ?>