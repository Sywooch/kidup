<?php
use yii\helpers\Html;

$this->title = \Yii::t('title', 'Admin') . ' - ' . Yii::$app->name;
?>
<section class="section container">
    <br/><br/>

    <div class="row">
        <div class="col-md-8 col-md-offset-2 card">
            <h3>Admin Page</h3>

            <i>Currently <?= $userCount ?> registered users</i>

            <h4>Generate CSV export</h4>

            <div class="row">

                <div class="col-md-8">
                    This will output the CSV file which can be loaded into the Danske Bank system. It is
                    <cite>important</cite>
                    that you directly upload the export to Danske bank and after that <b>delete all</b> the traces of
                    the file
                    from your pc. It is highly private information. The data will only be available once, don't f*ck it
                    up. Please ;)
                </div>
                <div class="col-md-4">
                    <?= Html::a('Generate CSV export', '@web/admin/payment/generate-payout',
                        [
                            'class' => 'btn btn-danger btn-fill',
                            'data-confirm' => 'ATTENTION: You\'re sure, right?',
                        ]
                    ) ?>
                </div>
            </div>
            <br/><br/>

        </div>
    </div>
</section>