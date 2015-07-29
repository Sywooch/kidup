<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = Yii::$app->name. ' - ' . \Yii::t('title', 'Error');
?>
<section class="section container">
    <div class="site-error">

        <h1 style="color:black">Woeps.. :(</h1>

        <div class="alert alert-danger" style="background-color: #ee7772">
            <?= Html::encode($this->title) .': '.nl2br(Html::encode($message)) ?>
        </div>

        <p>
            <?= Yii::t("app", "We are kind of embarresed, but an error occured. Please retry, and contact us if it fails again.") ?>
        </p>

    </div>

</section>
