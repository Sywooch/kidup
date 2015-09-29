<?php
namespace item\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class GoogleAutoComplete extends InputWidget
{
    const API_URL = 'http://maps.googleapis.com/maps/api/js?';
    public $libraries = 'places';
    public $sensor = true;
    public $autocompleteOptions = [];

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $elementId = $this->options['id'];
        $scriptOptions = json_encode($this->autocompleteOptions);
        $view = $this->getView();
        $view->registerJsFile(self::API_URL . http_build_query([
                'libraries' => $this->libraries,
                'sensor' => $this->sensor ? 'true' : 'false'
            ]));
        $autocompleteName = "autocomplete-";
        if(isset($this->options['autocompleteName'])){
            $autocompleteName .= $this->options['autocompleteName'];
        }
        $view->registerJs(<<<JS
            (function(){
                $(window).attr('$autocompleteName', new google.maps.places.Autocomplete(
                    document.getElementById('{$elementId}'),
                    {$scriptOptions}
                ));
            })();
JS
            , \yii\web\View::POS_READY);
    }
}