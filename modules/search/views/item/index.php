<?php
// set the title
$this->title = \Yii::t('title', '{0} KidStuff', ['query!']) . ' - ' . Yii::$app->name;

// load the assets
\app\modules\search\assets\SearchAsset::register($this);

// load the variables
$vars = [
    'model' => $model,
    'results' => $results
];

// load Angular
echo '<div ng-app="searchModule">';

// load Angular controller
echo '<div ng-controller="SearchController">';

// load the modal view
echo $this->render('method/modal/index', $vars);

// load the default view
echo $this->render('method/default/index', $vars);

echo '</div>';

echo '</div>';
?>