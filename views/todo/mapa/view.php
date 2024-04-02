<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Mapa: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Mapas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$attributes = [];

$attributes[] = 'nome';

$attributes[] = 'descricao';

if($model->campos)
{
    foreach($model->campos as $campo)
    {
        $attributes[] = 
        [
            'label' => $campo->campo->nome,
            'format' => 'raw',
            'value' => $campo->tag
        ];
    }
}

?>


<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <?= $this->render('_layouts/_top', compact('model')) ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">

                <div data-mh="painel-group-001" class="card card-consulta card--chart card--consuta__full" style="width: 100%;">

                    <div class="col-lg-12">

                        <div id="map" style="width:100%;height:860px;"></div>

                    </div>

                    <script>

                        var map;

                        var locations =
                            [
                                [
                                    [1,1,-16.700586,-49.243867,'02/03/2020','14:03', 'Caio César'],
                                    [2,1,-16.700226,-49.242987,'02/03/2020','14:33', 'Caio César'],
                                    [3,1,-16.700041,-49.242322,'02/03/2020','15:03', 'Caio César'],
                                    [4,1,-16.700288,-49.241893,'02/03/2020','15:33', 'Caio César'],
                                    [5,1,-16.700956,-49.241839,'02/03/2020','16:03', 'Caio César'],
                                    [6,1,-16.701439,-49.241925,'02/03/2020','16:33', 'Caio César'],
                                    [7,1,-16.702107,-49.242,'02/03/2020','17:03', 'Caio César'],
                                    [8,1,-16.702744,-49.242204,'02/03/2020','17:33', 'Caio César'],
                                    [9,1,-16.70333,-49.242311,'02/03/2020','18:03', 'Caio César'],
                                    [10,1,-16.703916,-49.242493,'02/03/2020','18:33', 'Caio César'],
                                    [11,1,-16.704646,-49.242708,'02/03/2020','19:03', 'Caio César']
                                ],
                                [
                                    [12,2,-16.702601,-49.245261,'02/03/2020','14:18', 'Murilo Aquino'],
                                    [13,2,-16.701748,-49.245615,'02/03/2020','14:48', 'Murilo Aquino'],
                                    [14,2,-16.700916,-49.245915,'02/03/2020','15:18', 'Murilo Aquino'],
                                    [15,2,-16.700012,-49.246269,'02/03/2020','15:48', 'Murilo Aquino'],
                                    [16,2,-16.698388,-49.246097,'02/03/2020','16:18', 'Murilo Aquino'],
                                    [17,2,-16.69885,-49.245797,'02/03/2020','16:48', 'Murilo Aquino'],
                                    [18,2,-16.6996,-49.245529,'02/03/2020','17:18', 'Murilo Aquino'],
                                    [19,2,-16.700494,-49.245207,'02/03/2020','17:48', 'Murilo Aquino']
                                ],
                            ];

                        var myStyles =[
                            {
                                featureType: "poi",
                                elementType: "labels",
                                stylers: [
                                    { visibility: "off" }
                                ]
                            }
                        ];

                        function initMap() {
                            var directionsService = new google.maps.DirectionsService;
                            var directionsRenderer = new google.maps.DirectionsRenderer({
                                suppressMarkers : true
                            });

                            map = new google.maps.Map(document.getElementById('map'), {
                                zoom: <?= $model->zoom ?>,
                                disableDefaultUI: true,
                                scrollwheel: false,
                                navigationControl: false,
                                mapTypeControl: false,
                                scaleControl: false,
                                draggable: false,
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                styles: myStyles,
                                center: {lat: <?= $model->latitude ?>, lng: <?= $model->longitude ?>}
                            });

                            directionsRenderer.setMap(map);
                            var infowindow = new google.maps.InfoWindow();
                            var marker, i;

                            var image = {
                                url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                                size: new google.maps.Size(20, 32),
                                origin: new google.maps.Point(0, 0),
                                anchor: new google.maps.Point(0, 32)
                            };

                            var shape = {
                                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                                type: 'poly'
                            };

                            var waypts = [];
                            var origin = new google.maps.LatLng(locations[<?= $device ?>][0][2], locations[<?= $device ?>][0][3]);
                            var destination = new google.maps.LatLng(locations[<?= $device ?>][locations[<?= $device ?>].length - 1][2], locations[<?= $device ?>][locations[<?= $device ?>].length - 1][3]);

                            for (i = 0; i < locations[<?= $device ?>].length; i++) {

                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[<?= $device ?>][i][2], locations[<?= $device ?>][i][3]),
                                    map: map,
                                    icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + (i + 1) + '|227584|FFFFFF',
                                });

                                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                    return function () {
                                        infowindow.setContent('<div id="content">'+
                                            '<div id="siteNotice">'+
                                            '</div>'+
                                            '<h5 id="firstHeading" class="firstHeading">Atualização - ' + (i + 1) + '</h5>'+
                                            '<div id="bodyContent">'+
                                            '<p>Aparelho: <b>#' + locations[<?= $device ?>][i][1] + '</b></p>'+
                                            '<p>Data: <b>' + locations[<?= $device ?>][i][4] + '</b></p>'+
                                            '<p>Horário: <b>' + locations[<?= $device ?>][i][5] + '</b></p>'+
                                            '<p>Responsável: <b>' + locations[<?= $device ?>][i][6] + '</b></p>'+
                                            '</div>'+
                                            '</div>');
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));

                                if(i > 0 && i < locations[<?= $device ?>].length - 1)
                                {
                                    waypts.push({
                                        location: new google.maps.LatLng(locations[<?= $device ?>][i][2], locations[<?= $device ?>][i][3]),
                                        stopover: true
                                    });
                                }
                            }

                            var request = {
                                origin: origin,
                                destination: destination,
                                waypoints: waypts,
                                optimizeWaypoints: true,
                                travelMode: google.maps.TravelMode.WALKING
                            };

                            directionsService.route(request, function (response, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    directionsRenderer.setDirections(response);
                                    directionsRenderer.setMap(map);
                                } else {
                                    alert("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
                                }
                            });
                        }

                    </script>

                    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCs1vpFHFzZwzQeQlkj8llB6BmY7ewzZnw&callback=initMap"></script>

                </div>
            </div>


        </div>

    </div>

</div>