<?php
namespace app\widgets;
use yii\widgets\InputWidget;
use yii\helpers\Html;

class Map extends \yii\bootstrap\Widget {
    public $longitude;
    public $latitude;
    public $addRandCircle;

    /**
     * Renders the widget.
     */
    public function init(){
        if($this->addRandCircle == true){
            $this->latitude += 0.0015;
            $this->longitude +=-0.001;
        }
        // first lets setup the center of our map
        $center = new \dosamigos\leaflet\types\LatLng([
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ]);

        // now lets create a marker that we are going to place on our map
        $marker = new \dosamigos\leaflet\layers\Marker(['latLng' => $center, 'popupContent' => 'Location']);
        $circle = new \dosamigos\leaflet\layers\Circle(['latLng' => $center, 'radius' => 400]);

        // The Tile Layer (very important)
        $tileLayer = new \dosamigos\leaflet\layers\TileLayer([
            'urlTemplate' => 'http://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg',
            'clientOptions' => [
                //'attribution' => 'Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> ' .
                 //   '<img src="http://developer.mapquest.com/content/osm/mq_logo.png">, ' .
                 //   'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                'subdomains' => '12324',
            ]
        ]);

        // now our component and we are going to configure it
        $leaflet = new \dosamigos\leaflet\LeafLet([
            'center' => $center, // set the center
            'clientOptions' => [
                'scrollWheelZoom' => false,
//                'height' => '400px'
            ]
        ]);
        // Different layers can be added to our map using the `addLayer` function.
        $leaflet->addLayer($marker)// add the marker
        ->addLayer($tileLayer);  // add the tile layer
        if($this->addRandCircle){
            $leaflet->addLayer($circle);
        }

        // finally render the widget
        echo \dosamigos\leaflet\widgets\Map::widget(['leafLet' => $leaflet]);
    }

}