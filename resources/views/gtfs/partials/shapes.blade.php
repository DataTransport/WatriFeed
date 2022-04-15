@extends('layouts.master')
@php
    $list_gtfs = 'active';
    $edit_ ='edit_';
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

        .bg-success {

            box-shadow: 0px 0px 9px black !important;
            background-color: #053ba0 !important;
            color: #fff !important;
        }

        .bg-success td {
            color: #fff !important;
        }
    </style>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
    <script src="/Leaflet.PolylineOffset/leaflet.polylineoffset.js"></script>

    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
          rel='stylesheet'/>


@stop

@section('content')
    <div class="col-md-12">
        @if (count($errors) > 0)



            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <span id="gtfs" hidden>{{$gtfs->id}}</span>
        <div class="row">
            <a href="{{route('gtfs.edit', ['gtf' =>$gtfs->id ])}}" class="btn btn-primary">Back</a>
            <hr>
            <div style="border: 1px solid #3c1f6f;" class="panel panel-primary " data-collapsed="0">

                <div class="col-sm-1" style="padding-top: 10px;font-size: 18px;">
                    <i style="color: #fff;font-size: xx-large;" class="fa fa-code-fork"></i>
                </div>
                <div style="background: #3c1f6f; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading"
                     class="panel-heading">
                    <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                        <div class="row">

                            <div class="col-sm-4">
                                Shapes
                            </div>

                            <div class="col-sm-4">
                                <form action="/shape" method="get">
                                    <input type="text" hidden value="{{csrf_token()}}" name="t">
                                    <input type="text" hidden value="{{$gtfs->id}}" name="g">
                                    <div class="input-group" style="width: 200px;">
                                        <input type="text" class="form-control" name="search" placeholder="ShapeID">
                                        <span class="input-group-btn">
                                        <button class="btn btn-success" type="submit">
                                            <i style="color:#fff;" class="entypo-search"></i>
                                        </button>

                                        </span>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4">
                                <form action="/delete-shape" method="post">
                                    @csrf
                                    {{--                                    <input type="text" hidden value="{{csrf_token()}}" name="t">--}}
                                    <input type="text" hidden value="{{$gtfs->id}}" name="g">
                                    <div class="input-group" style="width: 200px;">
                                        <input type="text" class="form-control" name="id" placeholder="ShapeID"
                                               required>
                                        <span class="input-group-btn">
                                        <button class="btn btn-danger"
                                                onclick="return confirm('Are you sure you would like to delete this shape');"
                                                type="submit">
                                            <i style="color:#fff;" class="fa fa-trash"></i>
                                        </button>

                                        </span>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>


                    <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">
                    Records {{ $shapes->firstItem() }} - {{ $shapes->lastItem() }} of {{ $shapes->total() }} (for page {{ $shapes->currentPage() }} )
                </span>
                        <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                        <a href="#add" id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>

                    </div>


                </div>
                <div class="panel-body" style="">

                    <table class="table table-bordered datatable" id="shapes_data">
                        <thead>
                        <tr>
                            <th>ShapeID <span class="field_required">*</span></th>
                            <th>Pt_lat <span class="field_required">*</span></th>
                            <th>Pt_lon <span class="field_required">*</span></th>
                            <th>Pt_sequence <span class="field_required">*</span></th>
                            <th>ShapeDistTraveled</th>
                            <th>A</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($shapes as $shape)
                            <tr class="odd gradeX">
                                <td class="update {{$shape->id}}" data-id="{{$shape->id}}"
                                    data-column="shape_id">{{$shape->shape_id}}</td>
                                <td class="update {{$shape->id}}" data-id="{{$shape->id}}"
                                    data-column="shape_pt_lat">{{$shape->shape_pt_lat}}</td>
                                <td class="update {{$shape->id}}" data-id="{{$shape->id}}"
                                    data-column="shape_pt_lon">{{$shape->shape_pt_lon}}</td>
                                <td class="update {{$shape->id}}" data-id="{{$shape->id}}"
                                    data-column="shape_pt_sequence">{{$shape->shape_pt_sequence}}</td>
                                <td class="update {{$shape->id}}" data-id="{{$shape->id}}"
                                    data-column="shape_dist_traveled">{{$shape->shape_dist_traveled}}</td>

                                <td>
                                    <button style="display: none;" type="button" name="save_btn"
                                            class="btn btn-success btn-sm save_btn{{$shape->id}} save_btn"
                                            data-rowid="{{$shape->id}}">save
                                    </button>
                                    <button type="button" name="edit_btn"
                                            class="btn btn-info btn-sm edit_btn{{$shape->id}} edit_btn"
                                            data-rowid="{{$shape->id}}"><i class="fa fa-edit"></i></button>
                                    <button type="button" name="delete" class="btn btn-danger btn-sm delete"
                                            id="{{$shape->id}}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <th>ShapeId</th>
                        <th>Pt_lat</th>
                        <th>Pt_lon</th>
                        <th>Pt_sequence</th>
                        <th>ShapeDistTraveled</th>
                        <th></th>
                        </tfoot>
                    </table>

                    {{ $shapes->links() }}


                </div>
            </div>
        </div>

    </div>

{{--    <div class="row">--}}
{{--        <div class="col-sm-12">--}}
{{--            <div class="panel panel-gradient" data-collapsed="0">--}}
{{--                <!-- panel head -->--}}
{{--                <div class="panel-heading">--}}
{{--                    <div class="panel-title">Map Visualization</div>--}}
{{--                    <div class="panel-options">--}}
{{--                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>--}}
{{--                        <a href="#" data-rel="reload" id="map_reload"><i class="entypo-arrows-ccw"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- panel body -->--}}
{{--                <div class="panel-body" id="mapcontainer">--}}

{{--                </div>--}}

{{--            </div>--}}

{{--        </div>--}}

{{--        <div class="col-sm-12">--}}
{{--            <div class="panel panel-gradient" data-collapsed="0">--}}
{{--                <!-- panel head -->--}}
{{--                <div class="panel-heading">--}}
{{--                    <div class="panel-title">Map Visualization</div>--}}
{{--                    <div class="panel-options">--}}
{{--                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>--}}
{{--                        <a href="#" data-rel="reload" id="map_reload"><i class="entypo-arrows-ccw"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- panel body -->--}}
{{--                <div class="panel-body">--}}
{{--                    <div id="tag_container">--}}
{{--                        @include('presult')--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}

{{--        </div>--}}

{{--    </div>--}}



{{--    <div class="logo" id="w_l" style="position: relative;top: -128px;">--}}

{{--        <h3 style="color: #000000; font-size: 34px;margin-bottom: 0px;font-family: unset;text-align: center;">Watri<span--}}
{{--                style="color: #003caf;">Feed</span></h3>--}}
{{--        <hr style="margin-top: 0px; margin-bottom: 4px;border: 0;border-top: 1px solid #ff0000;">--}}
{{--        <span style="font-size: 20px; color: #000000; font-weight: bold;">Map Loading...</span>--}}
{{--    </div>--}}
@endsection




@section('styles_page')
    {!! app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css') !!}
    {!! app('html')->style('neon/js/datatables/datatables.css') !!}
    {!! app('html')->style('neon/js/select2/select2-bootstrap.css') !!}
    {!! app('html')->style('neon/js/select2/select2.css') !!}
@stop

@section('scripts_page')
    {!! app('html')->script('neon/js/datatables/datatables.js') !!}
    {!! app('html')->script('neon/js/select2/select2.min.js') !!}
    {!! app('html')->script('neon/js/neon-chat.js') !!}
    {!! app('html')->script('neon/js/toastr.js') !!}


    {!! app('html')->script('dataTable/js/functions.js') !!}
    {!! app('html')->script('dataTable/js/shapes.js') !!}
    <script
        src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>


{{--    <script>--}}

{{--        $(document).ready(function () {--}}
{{--            // Custom--}}
{{--            var customElement = $("#w_l");--}}
{{--            // Let's call it 2 times just for fun...--}}
{{--            $("#map").LoadingOverlay("show", {--}}
{{--                image: "",--}}
{{--                fontawesome: "fa fa-cog fa-spin",--}}
{{--                fontawesomeColor: "#003caf",--}}
{{--                custom: customElement--}}
{{--            });--}}
{{--        });--}}

{{--        getMap('all');--}}

{{--        function getMap(id, zoom = 7, popups = 0) {--}}

{{--            $("#mapcontainer").html('<div id="map" style="width:100%; height:500px;border: solid #1066b4; padding: 0px;"></div>');--}}
{{--            $.ajaxSetup({--}}
{{--                headers: {--}}
{{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                }--}}
{{--            });--}}
{{--            $.ajax({--}}

{{--                url: "/shapes-map/",--}}
{{--                method: "get",--}}
{{--                data: {--}}
{{--                    id: id,--}}
{{--                },--}}
{{--                success: function (data) {--}}
{{--                    let tileLayerDefault = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';--}}
{{--                    let tileLayerEsri_WorldImagery = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';--}}
{{--                    console.log(data.shapes);--}}
{{--                    map = new L.Map('map', {--}}
{{--                        center: [data.shapes[0][0][1], data.shapes[0][0][0]],--}}
{{--                        zoom: zoom,--}}
{{--                        layers: [--}}
{{--                            L.tileLayer(tileLayerDefault, {--}}
{{--                                minZoom: 0,--}}
{{--                                maxZoom: 19,--}}
{{--                                attribution: '<span style="border: solid 1px #1066b4;padding: 0 6px;"><span style="color: #000;font-weight: bold">Watri</span><span style="color: #003caf;font-weight: bold">Feed</span></span> <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'--}}
{{--                            })--}}
{{--                        ]--}}
{{--                    });--}}

{{--                    map.addControl(new L.Control.Fullscreen());--}}

{{--                    let features = [];--}}
{{--                    for (const shape of data.shapes) {--}}
{{--                        // storing all letter and digit combinations--}}
{{--                        // for html color code--}}
{{--                        var letters = "0123456789ABCDEF";--}}

{{--                        // html color code starts with #--}}
{{--                        var color = '#';--}}

{{--                        // generating 6 times as HTML color code consist--}}
{{--                        // of 6 letter or digits--}}
{{--                        for (var i = 0; i < 6; i++)--}}
{{--                            color += letters[(Math.floor(Math.random() * 16))];--}}

{{--                        // console.log(color)--}}
{{--                        features.push({--}}
{{--                            "type": "Feature",--}}
{{--                            "properties": {--}}
{{--                                "lines": [color]--}}
{{--                            },--}}
{{--                            "geometry": {--}}
{{--                                "type": "LineString",--}}
{{--                                "coordinates": shape--}}
{{--                            }--}}
{{--                        });--}}
{{--                    }--}}
{{--                    const geoJson = {--}}
{{--                        "type": "FeatureCollection",--}}
{{--                        "features": features--}}
{{--                    };--}}

{{--                    var lineWeight = 6;--}}
{{--                    var lineColors = ['red', '#08f', '#0c0', '#f80'];--}}


{{--                    // manage overlays in groups to ease superposition order--}}
{{--                    var outlines = L.layerGroup();--}}
{{--                    var lineBg = L.layerGroup();--}}
{{--                    var busLines = L.layerGroup();--}}
{{--                    var busStops = L.layerGroup();--}}

{{--                    var ends = [];--}}

{{--                    function addStop(ll) {--}}
{{--                        for (var i = 0, found = false; i < ends.length && !found; i++) {--}}
{{--                            found = (ends[i].lat == ll.lat && ends[i].lng == ll.lng);--}}
{{--                        }--}}
{{--                        if (!found) {--}}
{{--                            ends.push(ll);--}}
{{--                        }--}}
{{--                    }--}}

{{--                    var lineSegment, linesOnSegment, segmentCoords, segmentWidth, p = 0;--}}
{{--                    geoJson.features.forEach(function (lineSegment) {--}}
{{--                        segmentCoords = L.GeoJSON.coordsToLatLngs(lineSegment.geometry.coordinates, 0);--}}
{{--                        linesOnSegment = lineSegment.properties.lines;--}}
{{--                        segmentWidth = linesOnSegment.length * (lineWeight + 1);--}}

{{--                        L.polyline(segmentCoords, {--}}
{{--                            color: '#000',--}}
{{--                            weight: segmentWidth + 5,--}}
{{--                            opacity: 1--}}
{{--                        }).addTo(outlines).bindPopup(data.popups[p]);--}}

{{--                        L.polyline(segmentCoords, {--}}
{{--                            color: '#fff',--}}
{{--                            weight: segmentWidth + 3,--}}
{{--                            opacity: 1--}}
{{--                        }).addTo(lineBg).bindPopup(data.popups[p]);--}}

{{--                        for (var j = 0; j < linesOnSegment.length; j++) {--}}
{{--                            l = L.polyline(segmentCoords, {--}}
{{--                                // color: lineColors[linesOnSegment[j]],--}}
{{--                                color: linesOnSegment[j],--}}
{{--                                weight: lineWeight,--}}
{{--                                opacity: 1,--}}
{{--                                offset: j * (lineWeight + 1) - (segmentWidth / 2) + ((lineWeight + 1) / 2)--}}
{{--                            }).addTo(busLines).bindPopup(data.popups[p]);--}}
{{--                            // if(popups===1){--}}
{{--                            //     l.openPopup();--}}
{{--                            // }--}}

{{--                        }--}}

{{--                        addStop(segmentCoords[0]);--}}
{{--                        addStop(segmentCoords[segmentCoords.length - 1]);--}}
{{--                        p++;--}}
{{--                    });--}}

{{--                    ends.forEach(function (endCoords) {--}}
{{--                        L.circleMarker(endCoords, {--}}
{{--                            color: '#000',--}}
{{--                            fillColor: '#ffffff',--}}
{{--                            fillOpacity: 1,--}}
{{--                            radius: 6,--}}
{{--                            weight: 2,--}}
{{--                            opacity: 1--}}
{{--                        }).addTo(busStops);--}}
{{--                    });--}}

{{--                    outlines.addTo(map);--}}
{{--                    lineBg.addTo(map);--}}
{{--                    busLines.addTo(map);--}}
{{--                    busStops.addTo(map);--}}


{{--                    // Here we might call the "hide" action 2 times, or simply set the "force" parameter to true:--}}
{{--                    $("#map").LoadingOverlay("hide", true);--}}

{{--                },--}}
{{--                error: function (data) {--}}
{{--                    const errors = $.parseJSON(data.responseText);--}}
{{--                    let message = '';--}}
{{--                    $.each(errors.errors, function (key, value) {--}}
{{--                        message += value + '<br>';--}}
{{--                    });--}}
{{--                    // messageFlash(message, 'error');--}}
{{--                }--}}

{{--            });--}}
{{--        }--}}

{{--    </script>--}}
{{--    <script type="text/javascript">--}}
{{--        $(window).on('hashchange', function () {--}}
{{--            if (window.location.hash) {--}}
{{--                var page = window.location.hash.replace('#', '');--}}
{{--                if (page == Number.NaN || page <= 0) {--}}
{{--                    return false;--}}
{{--                } else {--}}
{{--                    getData(page);--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}

{{--        $(document).ready(function () {--}}
{{--            $(document).on('click', '#tag_container .pagination a', function (event) {--}}
{{--                event.preventDefault();--}}

{{--                $('li').removeClass('active');--}}
{{--                $(this).parent('li').addClass('active');--}}

{{--                var myurl = $(this).attr('href');--}}
{{--                var page = $(this).attr('href').split('page=')[1];--}}

{{--                getData(page);--}}
{{--            });--}}


{{--        });--}}

{{--        function getData(page) {--}}
{{--            $.ajax(--}}
{{--                {--}}
{{--                    url: '?page=' + page,--}}
{{--                    type: "get",--}}
{{--                    datatype: "html"--}}
{{--                }).done(function (data) {--}}
{{--                $("#tag_container").empty().html(data);--}}
{{--                location.hash = page;--}}

{{--                $("#shapes_data2 tr").click(function () {--}}
{{--                    getMap($(this)[0].id, 12);--}}
{{--                    var selected = $(this).hasClass("bg-success");--}}
{{--                    $("#shapes_data2 tr").removeClass("bg-success");--}}
{{--                    if (!selected)--}}
{{--                        $(this).addClass("bg-success");--}}
{{--                });--}}
{{--            }).fail(function (jqXHR, ajaxOptions, thrownError) {--}}
{{--                alert('No response from server');--}}
{{--            });--}}
{{--        }--}}
{{--    </script>--}}
{{--    <script>--}}

{{--        $("#shapes_data2 tr.line_selectable").click(function () {--}}
{{--            $("#w_l").show();--}}
{{--            // Custom--}}
{{--            // var customElement = $("#w_l");--}}
{{--            // Let's call it 2 times just for fun...--}}
{{--            $("#map").LoadingOverlay("show", {--}}
{{--                image: "",--}}
{{--                fontawesome: "fa fa-cog fa-spin",--}}
{{--                fontawesomeColor: "#003caf",--}}
{{--            });--}}
{{--            getMap($(this)[0].id, 12, 1);--}}
{{--            var selected = $(this).hasClass("bg-success");--}}
{{--            $("#shapes_data2 tr.line_selectable").removeClass("bg-success");--}}
{{--            if (!selected)--}}
{{--                $(this).addClass("bg-success");--}}
{{--        });--}}

{{--        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {--}}
{{--            var R = 6371; // Radius of the earth in km--}}
{{--            var dLat = deg2rad(lat2 - lat1);  // deg2rad below--}}
{{--            var dLon = deg2rad(lon2 - lon1);--}}
{{--            var a =--}}
{{--                Math.sin(dLat / 2) * Math.sin(dLat / 2) +--}}
{{--                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *--}}
{{--                Math.sin(dLon / 2) * Math.sin(dLon / 2)--}}
{{--            ;--}}
{{--            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));--}}
{{--            var d = R * c; // Distance in km--}}
{{--            return d;--}}
{{--        }--}}

{{--        function deg2rad(deg) {--}}
{{--            return deg * (Math.PI / 180)--}}
{{--        }--}}
{{--    </script>--}}
@stop




