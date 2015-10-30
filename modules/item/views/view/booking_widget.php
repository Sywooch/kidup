<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * @var \app\extended\web\View $this
 * @var false|string $redirect
 * @var \item\forms\CreateBooking $model
 */

\yii\jui\JuiAsset::register($this);
\app\assets\LodashAsset::register($this);

$this->assetPackage = \app\assets\Package::ITEM_VIEW;
$this->registerJsVariables([
    'userIsGuest' => \Yii::$app->user->isGuest
]);
?>
<?php Pjax::begin([
//    'enableReplaceState' => true,
    'id' => 'pjax-create-booking-form',
    'timeout' => 20000
]);
?>
<script>
    if (typeof window.widget !== "undefined") {
        window.widget.load();
    }
</script>
<div class="col-md-3 card" id="booking-widget">
    <div class="row header">
        <div class="col-md-12">
            <h4>
                <?= Yii::t("item.view.booking_widget.title", "Book this product now!") ?>
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="shortTermPrice">
            <div class="col-md-4 ">
                <h4>
                    <?= $model->item->price_day !== null ? $model->item->price_day . ",-" : round($model->item->price_week / 7) . ",-" ?>
                </h4>

                <div class="times">
                    <?= Yii::t("item.view.booking_widget.per_day", "per day") ?>
                </div>
            </div>
            <div class="col-md-4">
                <h4>
                    <?= $model->item->price_week . ",-" ?>
                </h4>

                <div class="times">
                    <?= Yii::t("item.view.booking_widget.per_week", "per week") ?>
                </div>
            </div>
            <div class="col-md-4"
                 id="viewLongTerm" <?= \app\helpers\ViewHelper::trackClick("item.click_long_bookwidget",
                $model->item->id) ?>>
                <h4>
                    <i class="fa fa-angle-right"></i>
                </h4>

                <div class="times">
                    <div><?= Yii::t("item.view.booking_widget.view_long_term", "Long term") ?></div>
                </div>
            </div>
        </div>
        <div class="longTermPrice" style="display: none;">
            <div class="col-md-4 "
                 id="viewShortTerm" <?= \app\helpers\ViewHelper::trackClick("item.click_short_bookwidget",
                $model->item->id) ?>>
                <h4>
                    <i class="fa fa-angle-left"></i>
                </h4>

                <div class="times">
                    <div><?= Yii::t("item.view.booking_widget.view_short_term", "Short term") ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>
                    <?= $model->item->price_month !== null ? $model->item->price_month . ",-" : round($model->item->price_week * (30 / 7)) . ",-" ?>
                </h4>

                <div class="times">
                    <?= Yii::t("item.view.booking_widget.per_month", "per month") ?>
                </div>
            </div>
            <div class="col-md-4">
                <h4>
                    <?= $model->item->price_year !== null ? $model->item->price_year . ",-" :
                        ($model->item->price_month !== null ? round($model->item->price_month * 12) . ",-" : round($model->item->price_week * 52) . ",-") ?>
                </h4>

                <div class="times">
                    <?= Yii::t("item.view.booking_widget.per_year", "per year") ?>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'method' => 'get',
        'options' => ['name' => 'data-pjax', 'data-pjax' => true, 'id' => 'create-booking-form'],
    ]); ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="text">
                <?= Yii::t("item.view.booking_widget.starting_at", "Starting at") ?>
            </div>
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
                        'placeholder' => 'dd-mm-yyyy'
                    ],
                    'language' => Yii::$app->language == 'en' ? 'en-NZ' : Yii::$app->language,
                    'dateFormat' => 'dd-MM-yyyy',

                ])
            ?>
        </div>
        <div class="col-sm-6">
            <div class="text">
                <?= Yii::t("item.view.booking_widget.ending_at", "Ending at") ?>
            </div>
            <?php
            echo $form->field($model, 'dateTo',
                [
                    'options' => ['class' => 'drp-container form-group'],
                ])->label(false)->widget(DatePicker::className(),
                [
                    'clientOptions' => [
                        'beforeShowDay' => new JsExpression('widget.dateTo.beforeShowDay'),
                        'onSelect' => new JsExpression('widget.dateTo.onSelect')
                    ],
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'dd-mm-yyyy'
                    ],
                    'dateFormat' => 'dd-MM-yyyy',
                    'language' => Yii::$app->language == 'en' ? 'en-NZ' : Yii::$app->language,
                ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div>
                <?php if ($model->tableData):
                    $t = $model->tableData; ?>

                    <table class="table">
                        <tr>
                            <td>
                                <?= $t['price'][0] ?>
                            </td>
                            <td>
                                <?= $t['price'][1] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $t['fee'][0] ?>
                            </td>
                            <td>
                                <?= $t['fee'][1] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $t['total'][0] ?>
                            </td>
                            <td>
                                <?= $t['total'][1] ?>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>
                <?php if ($model->hasErrors()) {
                    foreach ($model->errors as $attr => $error) {
                        // echo $error[0];
                    }
                } ?>
                <?= Html::submitButton(\Yii::t("item.view.booking_widget.request_to_book_button",
                    'Request to Book'),
                    [
                        'class' => 'btn btn-danger btn-fill',
                        'style' => 'width:100%',
                        'id' => 'request-booking-btn',
                        'onclick' => \app\helpers\ViewHelper::trackClick("item.click_book_now", $model->item->id, false)
                    ]); ?>
                <div class="overlay">
                    <img alt=""
                         src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHdpZHRoPSI0MHB4IiBoZWlnaHQ9IjQwcHgiIHZpZXdCb3g9IjAgMCA0MCA0MCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWw6c3BhY2U9InByZXNlcnZlIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEuNDE0MjE7IiB4PSIwcHgiIHk9IjBweCI+CiAgICA8ZGVmcz4KICAgICAgICA8c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWwogICAgICAgICAgICBALXdlYmtpdC1rZXlmcmFtZXMgc3BpbiB7CiAgICAgICAgICAgICAgZnJvbSB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIC13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoLTM1OWRlZykKICAgICAgICAgICAgICB9CiAgICAgICAgICAgIH0KICAgICAgICAgICAgQGtleWZyYW1lcyBzcGluIHsKICAgICAgICAgICAgICBmcm9tIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKC0zNTlkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICB9CiAgICAgICAgICAgIHN2ZyB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSA1MCU7CiAgICAgICAgICAgICAgICAtd2Via2l0LWFuaW1hdGlvbjogc3BpbiAxLjVzIGxpbmVhciBpbmZpbml0ZTsKICAgICAgICAgICAgICAgIC13ZWJraXQtYmFja2ZhY2UtdmlzaWJpbGl0eTogaGlkZGVuOwogICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBzcGluIDEuNXMgbGluZWFyIGluZmluaXRlOwogICAgICAgICAgICB9CiAgICAgICAgXV0+PC9zdHlsZT4KICAgIDwvZGVmcz4KICAgIDxnIGlkPSJvdXRlciI+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwwQzIyLjIwNTgsMCAyMy45OTM5LDEuNzg4MTMgMjMuOTkzOSwzLjk5MzlDMjMuOTkzOSw2LjE5OTY4IDIyLjIwNTgsNy45ODc4MSAyMCw3Ljk4NzgxQzE3Ljc5NDIsNy45ODc4MSAxNi4wMDYxLDYuMTk5NjggMTYuMDA2MSwzLjk5MzlDMTYuMDA2MSwxLjc4ODEzIDE3Ljc5NDIsMCAyMCwwWiIgc3R5bGU9ImZpbGw6YmxhY2s7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNNS44NTc4Niw1Ljg1Nzg2QzcuNDE3NTgsNC4yOTgxNSA5Ljk0NjM4LDQuMjk4MTUgMTEuNTA2MSw1Ljg1Nzg2QzEzLjA2NTgsNy40MTc1OCAxMy4wNjU4LDkuOTQ2MzggMTEuNTA2MSwxMS41MDYxQzkuOTQ2MzgsMTMuMDY1OCA3LjQxNzU4LDEzLjA2NTggNS44NTc4NiwxMS41MDYxQzQuMjk4MTUsOS45NDYzOCA0LjI5ODE1LDcuNDE3NTggNS44NTc4Niw1Ljg1Nzg2WiIgc3R5bGU9ImZpbGw6cmdiKDIxMCwyMTAsMjEwKTsiLz4KICAgICAgICA8L2c+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwzMi4wMTIyQzIyLjIwNTgsMzIuMDEyMiAyMy45OTM5LDMzLjgwMDMgMjMuOTkzOSwzNi4wMDYxQzIzLjk5MzksMzguMjExOSAyMi4yMDU4LDQwIDIwLDQwQzE3Ljc5NDIsNDAgMTYuMDA2MSwzOC4yMTE5IDE2LjAwNjEsMzYuMDA2MUMxNi4wMDYxLDMzLjgwMDMgMTcuNzk0MiwzMi4wMTIyIDIwLDMyLjAxMjJaIiBzdHlsZT0iZmlsbDpyZ2IoMTMwLDEzMCwxMzApOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksMjguNDkzOUMzMC4wNTM2LDI2LjkzNDIgMzIuNTgyNCwyNi45MzQyIDM0LjE0MjEsMjguNDkzOUMzNS43MDE5LDMwLjA1MzYgMzUuNzAxOSwzMi41ODI0IDM0LjE0MjEsMzQuMTQyMUMzMi41ODI0LDM1LjcwMTkgMzAuMDUzNiwzNS43MDE5IDI4LjQ5MzksMzQuMTQyMUMyNi45MzQyLDMyLjU4MjQgMjYuOTM0MiwzMC4wNTM2IDI4LjQ5MzksMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxMDEsMTAxLDEwMSk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMy45OTM5LDE2LjAwNjFDNi4xOTk2OCwxNi4wMDYxIDcuOTg3ODEsMTcuNzk0MiA3Ljk4NzgxLDIwQzcuOTg3ODEsMjIuMjA1OCA2LjE5OTY4LDIzLjk5MzkgMy45OTM5LDIzLjk5MzlDMS43ODgxMywyMy45OTM5IDAsMjIuMjA1OCAwLDIwQzAsMTcuNzk0MiAxLjc4ODEzLDE2LjAwNjEgMy45OTM5LDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoMTg3LDE4NywxODcpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTUuODU3ODYsMjguNDkzOUM3LjQxNzU4LDI2LjkzNDIgOS45NDYzOCwyNi45MzQyIDExLjUwNjEsMjguNDkzOUMxMy4wNjU4LDMwLjA1MzYgMTMuMDY1OCwzMi41ODI0IDExLjUwNjEsMzQuMTQyMUM5Ljk0NjM4LDM1LjcwMTkgNy40MTc1OCwzNS43MDE5IDUuODU3ODYsMzQuMTQyMUM0LjI5ODE1LDMyLjU4MjQgNC4yOTgxNSwzMC4wNTM2IDUuODU3ODYsMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxNjQsMTY0LDE2NCk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMzYuMDA2MSwxNi4wMDYxQzM4LjIxMTksMTYuMDA2MSA0MCwxNy43OTQyIDQwLDIwQzQwLDIyLjIwNTggMzguMjExOSwyMy45OTM5IDM2LjAwNjEsMjMuOTkzOUMzMy44MDAzLDIzLjk5MzkgMzIuMDEyMiwyMi4yMDU4IDMyLjAxMjIsMjBDMzIuMDEyMiwxNy43OTQyIDMzLjgwMDMsMTYuMDA2MSAzNi4wMDYxLDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoNzQsNzQsNzQpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksNS44NTc4NkMzMC4wNTM2LDQuMjk4MTUgMzIuNTgyNCw0LjI5ODE1IDM0LjE0MjEsNS44NTc4NkMzNS43MDE5LDcuNDE3NTggMzUuNzAxOSw5Ljk0NjM4IDM0LjE0MjEsMTEuNTA2MUMzMi41ODI0LDEzLjA2NTggMzAuMDUzNiwxMy4wNjU4IDI4LjQ5MzksMTEuNTA2MUMyNi45MzQyLDkuOTQ2MzggMjYuOTM0Miw3LjQxNzU4IDI4LjQ5MzksNS44NTc4NloiIHN0eWxlPSJmaWxsOnJnYig1MCw1MCw1MCk7Ii8+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4K"
                         class="spinning-img">
                </div>
            </div>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>

<?php Pjax::end(); ?>

