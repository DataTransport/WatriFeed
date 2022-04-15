@extends('layouts.master')
@php
    $list_gtfs = 'active';
    $edit_ ='edit_';
    $ajax_select=true;
@endphp

@section('sidebar','sidebar-collapsed')

@section('add_head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-arrow {
            width: 10px !important;
        }

        .toast-message {
            width: 500px;
        }

        td {

            border: 2px solid black;
            color: #000;
        }

        .btn-block {
            font-size: large;
            font-weight: bold;
        }

        .select2-dropdown {
            z-index: 20001 !important;
        }

        .border_red {
            border: 1px red solid !important;
        }

        .select2-selection__placeholder {
            color: #000 !important;
        }

        body {
            color: black;
        }

    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>

    {{--    mapping--}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
          integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet-src.js"
            integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA=="
            crossorigin=""></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/leaflet.markercluster/example/screen.css"/>

    <link rel="stylesheet" href="/leaflet.markercluster/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="/leaflet.markercluster/dist/MarkerCluster.Default.css"/>
    <script src="/leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
    <script src="/leaflet.markercluster/example/realworld.10000.js"></script>

@stop

@section('content')
    <a href="{{route('stop.index')}}" class="btn btn-primary">Back</a>
    <hr>

        <div class="tab-pane" id="2">
            <div class="col-md-12">
                <div id="map" style="width:80%; height:580px"></div>
            </div>
        </div>

@endsection




@section('styles_page')
    {!! app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css') !!}
    {!! app('html')->style('neon/js/datatables/datatables.css') !!}
    {!! app('html')->style('neon/js/select2/select2-bootstrap.css') !!}
    {!! app('html')->style('neon/js/select2/select2.css') !!}
@stop

@section('scripts_page')
    {!! app('html')->script('neon/js/datatables/datatables.js') !!}
    {!! app('html')->script('neon/js/neon-chat.js') !!}

    <script src="/neon/js/bootstrap.js"></script>

    {!! app('html')->script('neon/js/toastr.js') !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    {!! app('html')->script('dataTable/js/functions.js') !!}
    {!! app('html')->script('dataTable/js/stops.js') !!}



    <script>


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({

            url: "/stop-map/",
            method: "get",
            data: {
                test: 'test',
            },
            success: function (data) {
                messageFlash(data, 'success');

                var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 18,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
                    }),
                    latlng = L.latLng(data[0].stop_lat, data[0].stop_lon);

                var map = L.map('map', {center: latlng, zoom: 10, layers: [tiles]});

                var markers = L.markerClusterGroup({chunkedLoading: true});

                var addressPoints = [];
                for (const stop of data) {
                    addressPoints.push([stop.stop_lat, stop.stop_lon, stop.stop_name, stop.stop_id]);
                }
                for (var i = 0; i < addressPoints.length; i++) {
                    var a = addressPoints[i];
                    var title = "[ "+a[3]+" ] "+a[2];
                    var marker = L.marker(L.latLng(a[0], a[1]), { title: title });
                    marker.bindPopup(title);
                    markers.addLayer(marker);
                }


                map.addLayer(markers);

                let latLongs = [];


            },
            error: function (data) {
                const errors = $.parseJSON(data.responseText);
                let message = '';
                $.each(errors.errors, function (key, value) {
                    message += value + '<br>';
                });
                messageFlash(message, 'error');
            }
        });

    </script>

@stop

