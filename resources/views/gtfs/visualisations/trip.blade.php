@extends('layouts.master')
@php
    $list_gtfs = 'active';
    $edit_ ='edit_';
@endphp

@section('sidebar','sidebar-collapsed')

@section('add_head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>


        td {

            /*border: 2px solid #1058ad;*/
            /*color: #000;*/
            font-size: 15px;
            /*text-align: center;*/
        }


        body {
            color: black;
        }

        .timebar.inexact {
            background-color: lightgreen;
        }

        .timebar {
            position: relative;
            height: 20px;
            background-color: gold;
            margin: 2px 0px;
        }

        .timebar .trail {
            height: 20px;
            background-color: gold;
        }

        th {
            /*text-align: center;*/
            /*font-weight: bold;*/
        }

        .bg_watri {
            background-color: #003471 !important;
        }

        .bg_watri_2 {
            background-color: #004403 !important;
        }

        .btn_font {
            /*font-size: 14px;*/
            font-weight: initial;
        }


        .panel > .panel-heading .panel-title {
            font-size: 16px;
            font-weight: bold;
        }

        .route_title {
            font-weight: bold;
            font-size: 13px !important;
        }

        .route_value {
            position: relative;
            float: right;
            font-weight: normal;
            font-size: 13px !important;
        }

        .panel-gradient {
            border-color: #1066b4;
        }

        .stop_name {
            font-size: 21px;
            position: relative;
            top: 5px;
            font-weight: bold;
        }

        .stop_first_name {
            color: #003471;
        }

        .stop_last_name {
            color: #0059a5;
        }

        .bg-success {

            box-shadow: 0px 0px 9px black !important;
            background-color: #278a25 !important;
            color: #fff;
        }

        .my-custom-scrollbar {
            position: relative;
            height: 200px;
            overflow: auto;
        }

        .table-wrapper-scroll-y {
            display: block;
        }

        legend {
            color: #1ea224;
            font-weight: bold;
            border-bottom: 1px solid #00974a;
        }

    </style>

    <!-- Leaflet -->
    {{--    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css"/>--}}
    {{--    <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>--}}

    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- Font Awesome 5 SVG -->
    <!-- <script defer src="https://use.fontawesome.com/releases/v5.6.3/js/all.js" integrity="sha384-EIHISlAOj4zgYieurP0SdoiBYfGJKkgWedPHH4jCzpCXLmzVsw1ouK59MuUtP4a1" crossorigin="anonymous"></script> -->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>

    <!-- Extra Markers -->
    <link rel="stylesheet" href="/Leaflet.ExtraMarkers_master/dist/css/leaflet.extra-markers.min.css"/>
    <script src="/Leaflet.ExtraMarkers_master/dist/js/leaflet.extra-markers.min.js"></script>

    {{--    Leaflet Polyline Decorator --}}
    <script src="/Leaflet.PolylineDecorator/dist/leaflet.polylineDecorator.js"></script>

    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"--}}
    {{--          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">--}}
@stop

@section('content')

    <div class="col-sm-4" style="height: 580px;overflow: auto;">
        <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
        <br>
        <br>
        @php
            $type = ['','','','Bus'];
        use App\helpers\WatriHelper;
        $calendar = \App\Calendar::where('service_id',$trip->service_id)->first();
            $array_stop = $trip->stops;
            if (isset($trip->stops[0]))
            $first_stop = $trip->stops[0];
            $last_stop = end($array_stop);
        @endphp

        <div class="panel panel-gradient panel-collapse" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Trip informations</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body" style="display: none;">
                <div>
                    <p><span class="route_title">Route ID</span> : <span
                            class="route_value badge badge-primary ">{{$route->route_id===""?'empty':$route->route_id}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Service Schedule</span>

                        :

                        <span class="route_value badge badge-primary ">
                            {{WatriHelper::serviceIdToString($trip->service_id)}}
                        </span>
                    </p>
                    <br>
                    <hr class="watri_hr">
                    <p><span class="route_title">Trip ID</span> : <span
                            class="route_value badge badge-primary ">{{$trip->trip_id}}</span></p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Trip Headsign</span> : <span
                            class="route_value badge badge-primary ">{{$trip->trip_headsign===""?'empty':$trip->trip_headsign}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Trip Short Name</span> : <span
                            class="route_value badge badge-primary ">{{$trip->trip_short_name===""?'empty':$trip->trip_short_name}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Direction</span> : <span
                            class="route_value badge badge-primary ">{{$trip->direction_id===""?'empty':$trip->direction_id==0?'Forward':'Backward'}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Block</span> : <span
                            class="route_value badge badge-primary ">{{$trip->block_id===""?'empty':$trip->block_id}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Shape ID </span> : <span
                            class="route_value badge badge-primary ">{{$trip->shape_id===""?'empty':$trip->shape_id}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Wheelchair Accessibility</span> : <span
                            class="route_value badge badge-primary ">{{$trip->wheelchair_accessible===""?'empty':$trip->wheelchair_accessible}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p><span class="route_title">Bikes Allowed</span> : <span
                            class="route_value badge badge-primary ">{{$trip->bikes_allowed===""?'empty':$trip->bikes_allowed}}</span>
                    </p>
                    <hr class="watri_hr">
                    <p>
                        <span class="route_value badge badge-primary "><a
                                href="inverse-shape/?shape_id={{$trip->shape_id}}" style="color: white">Inverse Shape direction</a></span>
                        <br>
                    </p>
                    <hr class="watri_hr">
                </div>
            </div>
        </div>
        <div class="panel panel-gradient" data-collapsed="0">
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Actions</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">
                <p>
                    <span class="btn btn-primary "><a href="inverse-shape/?shape_id={{$trip->shape_id}}"
                                                      style="color: white">Inverse direction</a></span>
                    <br>
                </p>
                <hr class="watri_hr">
                <fieldset>
                    <legend>Reset Stoptimes</legend>
                    <form action="{{ route('refresh-stoptimes') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="input-row">
                            <input type="hidden" name="trip_id" value="{{$trip->trip_id}}">

                            <label for="tag_depart" class="col-md-6 control-label">Time Depart (hh:mm:ss)
                                <input type="time" name="time_depart" id="tag_depart" step="1"
                                       value="{{$trip->stoptimes->first()->arrival_time}}">
                            </label>
                            <label for="tag_time" class="col-md-6 control-label">Time interval (hh:mm:ss)
                                <input type="time" name="time_interval" id="tag_time" step="1" value="00:00:00">
                                <br>
                                <input type="checkbox" name="interval_time_on" id=""> Active
                            </label>
                            <br>
                            <br>
                            <hr>
                        </div>
                        <div class="input-row">
                            <label for="tag_ref_latitude" class="col-md-6 control-label">Ref_latitude
                                <input type="text" name="ref_latitude" id="tag_ref_latitude"
                                       value="{{$trip->stops[0]->stop_lat}}">
                            </label>
                            <label for="tag_ref_longitude" class="col-md-6 control-label">Ref_longitude
                                <input type="text" name="ref_longitude" id="tag_ref_longitude"
                                       value="{{$trip->stops[0]->stop_lon}}">
                            </label>
                            <br>
                            <br>
                            <hr>
                        </div>
                        <div class="input-row">
                            <label for="tag_ref_latitude" class="col-md-12 control-label">Try by
                                <select name="by_latlon">
                                    <option value="latitudes">latitudes</option>
                                    <option value="longitudes">longitude</option>
                                </select>
                                | Speed <input type="number" placeholder="Medium Speed" name="speed" id="tag_speed" value="15">
                            </label>
                            <br>
                            <br>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Reset">
                    </form>
                </fieldset>

                <br>
                <hr class="watri_hr">
                <fieldset>
                    <legend>Exchange Stop Sequence</legend>
                    <form action="{{ route('inverse-sequence') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="input-row">
                            <label for="tag_first" class="col-md-6 control-label">First Stop id
                                <input type="text" name="first_stop" id="tag_first">
                            </label>
                            <label for="tag_second" class="col-md-6 control-label">Second Stop id
                                <input type="text" name="second_stop" id="tag_second">
                            </label>

                            <label for="tag_tird" class="col-md-6 control-label">Tird Stop id
                                <input type="text" name="tird_stop" id="tag_tird">
                            </label>
                            <label for="tag_fourth" class="col-md-6 control-label">Fourth Stop id
                                <input type="text" name="fourth_stop" id="tag_fourth">
                            </label>

                            <label for="tag_fifth" class="col-md-6 control-label">fifth Stop id
                                <input type="text" name="fifth_stop" id="tag_fifth">
                            </label>
                            <label for="tag_sixth" class="col-md-6 control-label">Sixth Stop id
                                <input type="text" name="sixth_stop" id="tag_sixth">
                            </label>

                            <br>
                            <br>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-4">

                            <input type="submit" value="Exchange Sequence" class="btn btn-primary">

                        </div>
                    </form>
                </fieldset>

                <br>
                <hr class="watri_hr">
                <fieldset>
                    <legend>Recalc time</legend>
                    <form action="{{ route('recalc-time') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="input-row">
                            <!--<label for="trip_id" class="col-md-6 control-label">Trip id-->
                            <!--    <input type="text" name="trip_id" id="trip_id">-->
                            <!--</label>-->
                            <label for="speed" class="col-md-12 control-label">Speed in Km
                                <input type="number" name="speed" id="speed" value="15">
                                 <input type="submit" value="Re-Calc time" class="btn btn-primary">
                            </label>

                            <br>
                            <br>
                        </div>
                </fieldset>

            </div>
        </div>

    </div>
    <div class="col-sm-8">
        <div class="panel panel-gradient" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Map Visualization</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload" id="map_reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">
                <div id="map" style="width:100%; height:500px;border: solid #1066b4; padding: 0px;"></div>
            </div>

        </div>
        <div>
            <span class="stop_first_name stop_name">{{$first_stop->stop_name}} </span><img src="/sign.png" alt="">
            <span class="stop_last_name stop_name">{{$last_stop->stop_name}} </span>
        </div>

    </div>

    <div class="col-sm-12">
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table style="width: 100%;" id="data" class="table table-dark table-striped table-bordered table-hover">
                @php

                    @endphp
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Stop Name</th>
                    <th scope="col">Stop ID</th>
                    <th scope="col">Arrival Time</th>
                    <th scope="col">Departure Time</th>
                    <th scope="col">Headsign</th>
                    <th scope="col">Timepoint Restrictions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trip->stoptimes as $stoptime)
                    <tr id="marker_{{$stoptime->stop_sequence}}">
                        <td>{{$stoptime->stop_sequence}}</td>
                        <td>{{$stoptime->stop_name}}</td>
                        <td>{{$stoptime->stop_id}}</td>
                        <td>
                            {{--                        <i class="entypo-clock  btn_font"></i>--}}
                            {{$stoptime->arrival_time}}

                        </td>
                        <td>
                            {{$stoptime->departure_time}}
                        </td>
                        <td>{{$stoptime->stop_headsign}}</td>
                        <td>{{$stoptime->timepoint}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>






@endsection




@section('styles_page')
    {!! app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css') !!}

@stop

@section('scripts_page')
    {{--    {!! app('html')->script('neon/js/neon-chat.js') !!}--}}

    {{--    <script src="/neon/js/bootstrap.js"></script>--}}

    {{--    {!! app('html')->script('neon/js/toastr.js') !!}--}}

    <script>
        // Creating map options
        var markers = [];
        refresh_map();

        function refresh_map() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({

                url: "/trip-map/",
                method: "get",
                data: {
                    test: 'test',
                },
                success: function (data) {
                    // messageFlash(data, 'success');
                    let s = 0, end = data.stops.length;
                    let zoom_stop = 0;
                    if (end > 4) {
                        zoom_stop = 4;
                    }
                    const mapOptions = {
                        // center: [data.shapes[zoom_stop].shape_pt_lat, data.shapes[zoom_stop].shape_pt_lon],
                        center: [data.stops[zoom_stop].stop_lat, data.stops[zoom_stop].stop_lon],
                        zoom: 9
                    };
                    // Creating a map object
                    map = new L.map('map', mapOptions);

                    // Creating a Layer object
                    const layer = new L.TileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png');

                    // Adding layer to the map
                    map.addLayer(layer);

                    // console.log(data.stops);

                    // console.log(end);
                    for (const stop of data.stops) {

                        if (s === 0) {
                            s++;
                            const marker = L.marker([parseFloat(stop.stop_lat), parseFloat(stop.stop_lon)], {
                                icon: L.ExtraMarkers.icon({
                                    icon: 'fa-number',
                                    shape: 'circle',
                                    markerColor: 'green',
                                    number: data.stopSequence[stop.stop_id],
                                }),
                                title: "marker_" + data.stopSequence[stop.stop_id]
                            });

                            // Adding popup to the marker
                            marker.bindPopup(stop.stop_name).openPopup().on('click', clickZoom);

                            // Adding marker to the map
                            marker.addTo(map);

                            markers.push(marker);
                            continue;
                        }
                        if (s === end - 1) {
                            s++;
                            const marker = L.marker([parseFloat(stop.stop_lat), parseFloat(stop.stop_lon)], {
                                icon: L.ExtraMarkers.icon({
                                    icon: 'fa-number',
                                    shape: 'circle',
                                    markerColor: 'black',
                                    number: data.stopSequence[stop.stop_id],
                                }),
                                title: "marker_" + data.stopSequence[stop.stop_id]
                            });
                            s++;
                            // Adding popup to the marker
                            marker.bindPopup(stop.stop_name).openPopup().on('click', clickZoom);

                            // Adding marker to the map
                            marker.addTo(map);

                            markers.push(marker);
                            continue;
                        }
                        s++;
                        const marker = L.marker([parseFloat(stop.stop_lat), parseFloat(stop.stop_lon)], {
                            icon: L.ExtraMarkers.icon({
                                icon: 'fa-number',
                                shape: 'circle',
                                markerColor: 'blue',
                                number: data.stopSequence[stop.stop_id],
                                // extraClasses: 'fa-spin'
                            }),
                            title: "marker_" + data.stopSequence[stop.stop_id]
                        });

                        // Adding popup to the marker
                        marker.bindPopup(stop.stop_name).openPopup().on('click', clickZoom);

                        // Adding marker to the map
                        marker.addTo(map);

                        markers.push(marker);
                    }
                    // Creating latlng object
                    let latlngs = [];
                    for (const shape of data.shapes) {
                        latlngs.push([parseFloat(shape.shape_pt_lat), parseFloat(shape.shape_pt_lon)]);
                    }
                    console.log(data.shapes);
                    // Creating a poly line
                    if (data.routeColor === '#') {
                        data.routeColor = '#1779c2';
                    }
                    console.log(data.routeColor);
                    const polyline = L.polyline(latlngs, {color: data.routeColor});

                    L.polylineDecorator(polyline, {
                        patterns: [
                            {
                                offset: 25,
                                repeat: 150,
                                symbol: L.Symbol.arrowHead({pixelSize: 15, pathOptions: {fillOpacity: 1, weight: 0}})
                            }
                        ],
                        color: data.routeColor
                    }).addTo(map);

                    // Adding to poly line to map
                    polyline.addTo(map);

                    // Attribution options
                    var attrOptions = {
                        prefix: '<div style="border: solid 1px #1066b4;padding: 0 6px;"><span style="color: #000;font-weight: bold">Watri</span><span style="color: #003caf;font-weight: bold">Feed</span></div>'
                    };

                    // Creating an attribution
                    var attr = L.control.attribution(attrOptions);
                    attr.addTo(map);  // Adding attribution to the map


                },
                error: function (data) {
                    const errors = $.parseJSON(data.responseText);
                    let message = '';
                    $.each(errors.errors, function (key, value) {
                        message += value + '<br>';
                    });
                    // messageFlash(message, 'error');
                }
            });
        }


        function clickZoom(e) {
            map.setView(e.target.getLatLng(), 15);
        }

        function markerFunction(id) {
            for (var i in markers) {
                var markerID = markers[i].options.title;
                var position = markers[i].getLatLng();
                if (markerID == id) {
                    map.setView(position, 15);
                    markers[i].openPopup();
                }
            }
        }

        // $('document').ready(function () {
        $("tr").click(function () {
            markerFunction($(this)[0].id);

        });

        $("#data tr").click(function () {
            var selected = $(this).hasClass("bg-success");
            $("#data tr").removeClass("bg-success");
            if (!selected)
                $(this).addClass("bg-success");
        });
        // });

    </script>

@stop

