<?php
/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package tourline
 */

namespace common\components;


use yii\web\View;

class LatLngFinder extends \yii\base\Widget
{
    /**
     * @var string $latAttribute Latitude attribute id
     */
    public $latAttribute = null;

    /**
     * @var string $lngAttribute Longitude attribute id
     */
    public $lngAttribute = null;

    /**
     * @var string $zoomAttribute Zomm attribute id
     */
    public $zoomAttribute = null;

    /**
     * @var string $mapCanvasId Map canvas id
     */
    public $mapCanvasId = null;

    /**
     * @var integer $mapWidth Width of the map canvas
     */
    public $mapWidth = null;

    /**
     * @var integer $mapHeight Height of the map canvas
     */
    public $mapHeight = null;

    /**
     * @var float $defaultLat Default Latitude for the map
     */
    public $defaultLat = null;

    /**
     * @var float $defaultLng Default Longitude for the map
     */
    public $defaultLng = null;

    /**
     * @var integer $defaultZoom Default initial Zoom for the map
     */
    public $defaultZoom = null;

    /**
     * @var bool $enableZoomField If set to boolean true then the zoom value will be assinged to the zoom field
     */
    public $enableZoomField = null;

    /**
     * @var object $model Object model
     */
    public $model = null;


    public function init()
    {
        parent::init();

        $this->model = (isset($this->model)) ? $this->model : null;

        if ($this->model) {
            $formName = strtolower($this->model->formName());

            $this->latAttribute = (isset($this->latAttribute)) ? $formName . '-' . $this->latAttribute : $formName . '-' . 'lat';
            $this->lngAttribute = (isset($this->lngAttribute)) ? $formName . '-' . $this->lngAttribute : $formName . '-' . 'lng';
            $this->zoomAttribute = (isset($this->zoomAttribute)) ? $formName . '-' . $this->zoomAttribute : $formName . '-' . 'zoom';

            if (!$this->model->isNewRecord) {
                $this->defaultLat = $this->model->lat;
                $this->defaultLng = $this->model->lon;
            }
        } else {
            $this->latAttribute = (isset($this->latAttribute)) ? $this->latAttribute : 'lat';
            $this->lngAttribute = (isset($this->lngAttribute)) ? $this->lngAttribute : 'lng';
            $this->zoomAttribute = (isset($this->zoomAttribute)) ? $this->zoomAttribute : 'zoom';
        }

        $this->mapCanvasId = (isset($this->mapCanvasId)) ? $this->mapCanvasId : 'map';
        $this->mapWidth = (isset($this->mapWidth)) ? $this->mapWidth : 450;
        $this->mapHeight = (isset($this->mapHeight)) ? $this->mapHeight : 300;
        $this->defaultLat = (isset($this->defaultLat)) ? $this->defaultLat : -34.397;
        $this->defaultLng = (isset($this->defaultLng)) ? $this->defaultLng : 150.644;
        $this->defaultZoom = (isset($this->defaultZoom)) ? $this->defaultZoom : 8;
        $this->enableZoomField = (isset($this->enableZoomField)) ? (($this->enableZoomField == true) ? 1 : 0) : true;

        $this->registerAssets();

    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        $lat = $this->model->isNewRecord ? 41.2995 : $this->model->lat;
        $lon = $this->model->isNewRecord ? 69.2401 : $this->model->lon;

        $js = <<<SCRIPT

    		var map = null;
    		var marker = null;
    		var markers = [];
    		var enalbeZoom = $this->enableZoomField;

			function initMap() {
			  	var mapOptions = {
		            zoom: $this->defaultZoom,
		            center: {lat: $this->defaultLat, lng: $this->defaultLng},
		            mapTypeId: google.maps.MapTypeId.ROADMAP
		        };

				map = new google.maps.Map(document.getElementById('$this->mapCanvasId'), mapOptions);

				google.maps.event.addListener(map, 'click', function(e) {
	            	placeMarker(e.latLng, map);
	        	});
				
				google.maps.event.addListener(map, 'zoom_changed', function(e) {
		            var zoom = map.getZoom();
		            if (enalbeZoom) { document.getElementById('$this->zoomAttribute').value = zoom; }
        		});
        		
                placeMarker(new google.maps.LatLng({lat: $lat, lng: $lon}), map);

			}

			initMap();

			function placeMarker(position, map) {
				if (!marker) {
		            marker = new google.maps.Marker({
		                position: position,
		                draggable: true,
		                map: map
		            });
					
					var lat = position.lat();
					var lng = position.lng();
		            var zoom = map.getZoom();
		            
		            document.getElementById('$this->latAttribute').value = lat;
		            document.getElementById('$this->lngAttribute').value = lng;
		            if (enalbeZoom) { document.getElementById('$this->zoomAttribute').value = zoom; }

		            google.maps.event.addListener(marker, 'drag', function(e) {
		                var lat = e.latLng.lat();
		                var lng = e.latLng.lng();
		                var zoom = map.getZoom();
		                
			            document.getElementById('$this->latAttribute').value = lat;
			            document.getElementById('$this->lngAttribute').value = lng;
			            if (enalbeZoom) { document.getElementById('$this->zoomAttribute').value = zoom; }
		            });

		            map.panTo(position);
		            markers.push(marker);
		            markers[0].setMap(map);
		        }
			}


SCRIPT;

        $this->getView()->registerJs($js);

        echo '<div id="' . $this->mapCanvasId . '" style="width:' . $this->mapWidth . 'px;height:' . $this->mapHeight . 'px; margin-bottom: 20px;"></div>';

    }

    protected function registerAssets()
    {
        $view = $this->getView();
        $view->registerJsFile('https://maps.googleapis.com/maps/api/js?key=' . getenv('GOOGLE_MAPS_API_KEY'),
            ['position' => View::POS_HEAD]);
    }
}
