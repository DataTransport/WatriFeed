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
            height: 500px;
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


    <div class="col-sm-12">
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <span>{{$duplicates->count()}}</span> <a class="btn btn-danger" href="/deleted-stops-duplicates">Deleted all</a>
            
            <table style="width: 100%;" id="data" class="table table-dark table-striped table-bordered table-hover">
                @php

                    @endphp
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Stop Name</th>
                    <th scope="col">Stop ID</th>
                    <th scope="col">StopTime number</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($duplicates as $stop)
                    <tr>
                        <td> </td>
                        <td>{{$stop->stop_name}}</td>
                        <td>{{$stop->stop_id}}</td>
                        <td>{{\App\Stoptime::where('stop_id',$stop->stop_id)->where('gtfs_id',session('gtfs_id'))->count()}}</td>
                        <td></td>
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



@stop

