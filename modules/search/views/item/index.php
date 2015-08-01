<?php
use app\modules\search\assets\ItemSearchAsset;

// set the title
$this->title = \Yii::t('title', '{0} KidStuff', ['query!']) . ' - ' . Yii::$app->name;

// load the assets
ItemSearchAsset::register($this);

// load the variables
$vars = [
    'model' => $model,
    'results' => $results
];

// load search module
echo '<div id="itemSearch">';

// load Angular
echo '<div ng-app="ItemSearchModule">';

// load Angular controller
echo '<div ng-controller="ItemSearchController">';

// load the modal view
echo $this->render('method/modal/index', $vars);

// load the default view
echo $this->render('method/default/index', $vars);

echo '</div>';

echo '</div>';

echo '</div>';
?>