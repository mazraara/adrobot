<?php
use yii\web\View;
use yii\helpers\Html;
?>

<?php
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDG0VQozMKHkNtmMZJH2i-jKrGx-9Fx6Ug&callback=initMap', ['position' => View::POS_END]);

$script = <<< JS
    var map;
    var marker;
    var geocoder;

    function initMap() {
        var latlng = new google.maps.LatLng(51.5073509,-0.127);
        geocoder = new google.maps.Geocoder();

        map = new google.maps.Map(document.getElementById('map'), {
            center: latlng,
            zoom: 8
        });

        marker = new google.maps.Marker({
			map:map,
			draggable:false,
			animation: google.maps.Animation.DROP,
			position: latlng
		});
    }

    function findLocation() {
        var address = $('#address').val();
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                map.setZoom(16);
            } else {
                //alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    function setExistingData() {
        var adddress = window.parent.$('#address').val();
		var geoLocation = window.parent.$('#geoLocation').val();
		var geoData = geoLocation.split(',');
		var latlng = new google.maps.LatLng(geoData[0], geoData[1]);

		$('#address').val(adddress);

		if ('' != geoData) {
            marker.setPosition(latlng);
            map.setCenter(latlng);
            map.setZoom(16);
		}
    }

    function save() {
        var address = $('#address').val();
        var latLang = marker.getPosition();
		var longlat = latLang.lat() + ',' + latLang.lng();
		window.parent.$('#address').val(address);
		window.parent.$('#geoLocation').val(longlat);
    }
JS;

$this->registerJs($script, View::POS_HEAD);
?>

<?php
$script = <<< JS
    $( document ).ready(function() {
        $(document).on('click', '#find', function(e) {
            findLocation();
        });

        $(document).on('click', '#save', function(e) {
            save();
            parent.$.fancybox.close();
        });

        setExistingData();
    });
JS;

$this->registerJs($script, View::POS_END);
?>

<div class="row">
    <div class="col-xs-8">
        <?= Html::input('text', 'address', '', ['class' => 'form-control', 'id' => 'address', 'placeholder' => Yii::t('app', 'Address')]) ?>
    </div>
    <div class="col-xs-2">
        <?= Html::button('Find', ['class' => 'btn btn-info btn-block', 'id' => 'find', 'style' => 'margin-left:-25px']) ?>
    </div>
    <div class="col-xs-2">
        <?= Html::button('Save', ['class' => 'btn btn-info btn-block', 'id' => 'save', 'style' => 'margin-left:-45px']) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div style="border: solid; border-color: #CCCCCC; padding:5px; border-width: 1px; margin-top: 5px">
            <div id="map" style="width:100%;height:363px;"></div>
        </div>
    </div>
</div>








