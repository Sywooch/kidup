<?php
// set the title
$this->title = \Yii::t('title', '{0} KidStuff', ['query!']) . ' - ' . Yii::$app->name;

// load the assets
\app\modules\search\assets\ItemAsset::register($this);

// load the modal view
echo $this->render('method/modal/index');

// load the default view
echo $this->render('method/default/index');
?>