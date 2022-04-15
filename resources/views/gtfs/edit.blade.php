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

        tr td:first-child {
            font-size: larger;
            color: #003470;
            font-weight: bold;
        }

        input[type="file"] {
            display: inline;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: 100px;
            border-radius: 2px;
            cursor: pointer;
        }

        .outer-scontainer table {
            border-collapse: collapse;
            width: 100%;
        }

        .outer-scontainer th {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .outer-scontainer td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        #response {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 2px;
            display: none;
        }

        .success {
            background: #c7efd9;
            border: #bbe2cd 1px solid;
        }

        .error {
            background: #9c1a16;
            border: #f3c6c7 1px solid;
            color: #fff;
        }

        div#response.display-block {
            display: block;
        }

        .btn-primary {
            color: #ffffff;
            background-color: #0855b1;
            border-color: #f0f0f1;
        }

        .btn-primary:hover {
            color: #ffffff !important;
            background-color: #003471;
            border-color: #f0f0f1;
        }

        .btn-primary.btn-icon i {
            background-color: #003471;
        }

        .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
            border: 2px solid #003471;
        }

        .table-bordered > thead > tr > th, .table-bordered > thead > tr > td {
            background-color: #003471;
            border-bottom-width: 0px;
            color: #ffffff !important;
            border-bottom: 0 !important;
        }

        .input-row {
            padding: 10px;
        }
        hr {
            margin-top: 17px;
            margin-bottom: 5px;
            border: 0;
            border-top: 2px solid #3F51B5;
        }
        .form-horizontal .control-label {
            text-align: right;
            margin-bottom: 0;
            padding-top: 0px;
        }

        .td_total{
            color: black;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        footer {
            position: relative !important;
        }
    </style>

@stop

@section('content')
    <div class="col-md-12">
        <div id="progress_bar_"></div>
        {{session('row_current')}}
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


        <span id="gtfs" hidden>{{$gtfs}}</span>

        <div style="border: 1px solid black;" class="panel panel-primary" data-collapsed="0">

            <div style="border-bottom: 1px solid #000000;" class="panel-heading">
                <div style="font-weight: bold;font-size: 18px; text-align: center"
                     class="col-sm-offset-2 col-sm-6 panel-title">
                    GTFS - {{$gtfs->name}}
                </div>

                <div class="panel-options">
                    <button style="font-size: 13px;" onclick="openModal1('stops','extra-import-geojson')"
                            class="delete_gtfs btn btn-primary btn-sm btn-icon icon-left">
                        <i class="fa fa-magic"></i>
                        Import OSM DATA
                    </button>
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a style="font-size: 13px;" id="setting"  data-gtfs="{{$gtfs->name}}" href="" data-rel="reload" >
                        Settings
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered datatable col-sm-12" id="table-4">
                    <thead>
                    <tr>
                        <th>File</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="odd gradeX">
                        <td><i class="fa fa-home"></i> agency.txt</td>
                        <td class="td_total"> {{$gtfs->agencies()->count()}} </td>
                        <td>
                            <a href="/agency?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('agency','agencies-import')"
                                    class="delete_gtfs btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>

                            <a href="/agencies-export/" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-map-marker"></i> stops.txt</td>
                        <td class="td_total"> {{$gtfs->stops()->count()}} </td>
                        <td>
                            <a href="/stop?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('stops','stops-import')"
                                    class="delete_gtfs btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a href="/stops-export/" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-road"></i> routes.txt</td>
                        <td class="td_total"> {{$gtfs->routes()->count()}} </td>
                        <td>
                            <a href="/route?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('routes','routes-import')"
                                    class="delete_gtfs btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a href="/routes-export/" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-exchange"></i> trips.txt</td>
                        <td class="td_total"> {{$gtfs->trips()->count()}} </td>
                        <td>
                            <a href="/trip?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('trips','trips-import')"
                                    class="delete_gtfs btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a href="/trips-export/" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-clock-o"></i> stop_times.txt</td>
                        <td class="td_total"> {{$gtfs->stoptimes()->count()}} </td>
                        <td>
                            <a href="/stoptime?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('stoptimes','stoptimes-import')"
                                    class="delete_gtfs btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a href="{{route('stoptimes-export')}}" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>

                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-code-fork"></i> shapes.txt</td>
                        <td class="td_total"> {{$gtfs->shapes()->count()}} </td>
                        <td>
                            <a href="/shape?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('shapes','shapes-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a href="{{route('shapes-export')}}" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-line-chart"></i> frequencies.txt</td>
                        <td class="td_total"> {{$gtfs->frequencies()->count()}} </td>
                        <td>
                            <a href="/frequency?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('frequencies','frequencies-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                            <button onclick="openModal3('Generate Frequencies','generate-frequencies')"
                                    class="delete_gtfs btn btn-primary btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Generate
                            </button>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-calendar-o"></i> calendar.txt</td>
                        <td class="td_total"> {{$gtfs->calendars()->count()}} </td>
                        <td>
                            <a href="/calendar?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('calendars','calendars-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-calendar"></i> calendar_dates.txt</td>
                        <td class="td_total"> {{$gtfs->calendar_dates()->count()}} </td>
                        <td>
                            <a href="/calendardate?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('calendar_dates','calendar_dates-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-money"></i> fare_attributes.txt</td>
                        <td class="td_total"> {{$gtfs->fare_attributes()->count()}} </td>
                        <td>
                            <a href="/fareattribute?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('fare_attributes','fare_attributes-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td><i class="fa fa-chain"></i> fare_rules.txt</td>
                        <td class="td_total"> {{$gtfs->fare_rules()->count()}} </td>
                        <td>
                            <a href="/farerule?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('fare_rules','fare_rules-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>

                    <tr class="odd gradeX">
                        <td>pathways.txt</td>
                        <td class="td_total"> {{$gtfs->pathways()->count()}} </td>
                        <td>
                            <a href="/pathway?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('pathways','pathways-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>

                    <tr class="odd gradeX">
                        <td><i class="fa fa-exchange"></i> transfers.txt</td>
                        <td class="td_total"> {{$gtfs->transfers()->count()}} </td>
                        <td>
                            <a href="/transfer?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('transfers','transfers-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td>levels.txt</td>
                        <td class="td_total" > {{$gtfs->levels()->count()}} </td>
                        <td>
                            <a href="/level?t={!! csrf_token() !!}&g={{$gtfs->id}}&_={{$gtfs->password}}"
                               class="edit_gtfs btn btn-info btn-sm btn-icon icon-left">
                                <i class="entypo-pencil"></i>
                                Edit
                            </a>

                            <button onclick="openModal('levels','levels-import')"
                                    class=" btn btn-default btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Import
                            </button>
                            <a class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="entypo-download"></i>
                                Export
                            </a>
                        </td>
                    </tr>

                    </tbody>
                </table>

            </div>

        </div>


    </div>

    <!-- Modal 1 (Basic)-->
    <div class="modal fade" id="modal-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Basic Modal</h4>
                </div>

                <div class="modal-body">
                    <div id="response" class="<?php if (!empty($type)) {
                        echo $type . " display-block";
                    } ?>"><?php if (!empty($message)) {
                            echo $message;
                        } ?></div>
                    <form class="form-horizontal" action="{{ route('stops-import') }}" method="post"
                          name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                        @csrf
                        <div class="input-row">
                            <label class="col-md-4 control-label">
                                <span class="modal-label">Choose CSV</span>
                                File</label> <input type="file" name="file"
                                                    id="file" accept=".csv">
                            <button type="submit" id="submit" name="import"
                                    class="btn-submit">Import
                            </button>
                            <br/>
                        </div>


                    </form>

                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-2">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Basic Modal</h4>
                </div>

                <div class="modal-body">
                    <div id="response1" class="<?php if (!empty($type)) {
                        echo $type . " display-block";
                    } ?>"><?php if (!empty($message)) {
                            echo $message;
                        } ?></div>
                    <form class="form-horizontal" action="{{ route('stops-import') }}" method="post"
                          name="frmGeoJsonImport" id="frmGeoJsonImport" enctype="multipart/form-data">
                        @csrf
                        <div class="input-row">
                            <label class="col-md-4 control-label">
                                <span class="modal-label">Choose GeoJson</span>
                                File</label> <input type="file" name="file"
                                                    id="file2" accept=".geojson">
                            <button type="submit" id="submit" name="import"
                                    class="btn-submit">Import
                            </button>
                            <br>
                            <br>
                            <hr>
                        </div>
                        <div class="input-row">
                            <label for="tag_route" class="col-md-6 control-label">Prefix-Route
                                <input type="text" name="prefix_route" id="tag_route">
                            </label>
                            <label for="tag_trip" class="col-md-6 control-label">Trips direction
                                <input type="number" name="trip_direction" id="tag_trip">
                            </label>

                            <br>
                            <br>
                            <hr>
                        </div>
                        <div class="input-row">
                            <label for="tag_depart" class="col-md-5 control-label">Time Depart (hh:mm:ss)
                                <input type="time" name="time_depart" id="tag_depart" step="1" value="05:00:00">
                            </label>
                            <label for="tag_time" class="col-md-7 control-label">Time interval (hh:mm:ss)
                                <br>
                                <input type="time" name="time_interval" id="tag_time" step="1" value="00:01:00">
                                <input type="checkbox" name="interval_time_on" id=""> Active
                            </label>
                            <br>
                            <br>
                            <hr>
                        </div>
                        <div class="input-row">
                            <label for="tag_ref_latitude" class="col-md-6 control-label">Ref_latitude
                                <input type="text" name="ref_latitude" id="tag_ref_latitude">
                            </label>
                            <label for="tag_ref_longitude" class="col-md-6 control-label">Ref_longitude
                                <input type="text" name="ref_longitude" id="tag_ref_longitude">
                            </label>
                            <br>
                            <br>
                            <hr>
                        </div>
                        <div class="input-row">
                            <label for="tag_ref_latitude" class="col-md-8 control-label">Try by
                                <select name="by_latlon">
                                    <option value="latitudes">latitudes</option>
                                    <option value="longitudes">longitude</option>
                                </select>
                                | Speed <input type="number" placeholder="Medium Speed" name="speed" id="tag_speed" value="15">
                            </label>

                            <br>
                            <br>
                            <hr>
                        </div>


                    </form>

                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-3">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Basic Modal</h4>
                </div>

                <div class="modal-body">
                    <div id="response2" class="<?php if (!empty($type)) {
                        echo $type . " display-block";
                    } ?>"><?php if (!empty($message)) {
                            echo $message;
                        } ?></div>
                    <form class="form-horizontal" action="{{ route('stoptimes-generate') }}" method="post"
                          name="stoptimesGenerate" id="stoptimesGenerate" enctype="multipart/form-data">
                        @csrf
                        <div class="input-row">
                            <label for="start_time" class="col-md-6 control-label">Start time
                                <input type="time" name="start_time" id="start_time" step="1" value="05:00:00">
                            </label>
                            <label for="end_time" class="col-md-6 control-label">End time
                                <input type="time" name="end_time" id="end_time" step="1" value="23:00:00">
                            </label>
                        </div>
                        <hr>
                        <div class="input-row">
                            <div class="col-md-3"></div>
                            <label for="headway" class="col-md-6 control-label">Headway
                                <input type="number" name="headway_secs" id="headway" step="1">
                            </label>
                        </div>
                        <hr>

                            <button id="submit" name="generate"
                                    class="col-sm-offset-5 btn-submit">generate
                            </button>



                    </form>

                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection




@section('styles_page')
    {!! app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css') !!}
@stop

@section('scripts_page')

    {!! app('html')->script('neon/js/neon-chat.js') !!}
    {!! app('html')->script('neon/js/toastr.js') !!}


    <script>
        function post(path, params, method = 'post') {

            // The rest of this code assumes you are not using a library.
            // It can be made less wordy if you use one.
            const form = document.createElement('form');
            form.method = method;
            form.action = path;

            for (const key in params) {
                if (params.hasOwnProperty(key)) {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = key;
                    hiddenField.value = params[key];

                    form.appendChild(hiddenField);
                }
            }


            document.body.appendChild(form);
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    }
                });
            });
            form.submit();
        }

        $('#setting').on('click', function () {

            post('/gtfs/setting', {gtfs: $(this).data('gtfs'), "_token": "{{ csrf_token() }}"})
        });

        function openModal(file, action) {
            $('.modal-title').html(`Import ${file} CSV`);
            $('.modal-label').html(`Import ${file} CSV`);
            $('#frmCSVImport').attr('action', `/${action}`);
            jQuery('#modal-1').modal('show');
        }

        function openModal1(file, action) {
            $('.modal-title').html(`Import ${file} GeoJson`);
            $('.modal-label').html(`Import ${file} GeoJson`);
            $('#frmGeoJsonImport').attr('action', `/${action}`);
            jQuery('#modal-2').modal('show');
        }

        function openModal3(file, action) {
            $('.modal-title').html(`${file}`);
            $('.modal-label').html(`${file}`);
            $('#stoptimesGenerate').attr('action', `/${action}`);
            jQuery('#modal-3').modal('show');
        }

        $(document).ready(function () {
            $("#frmCSVImport").on("submit", function () {

                response = $("#response");
                response.attr("class", "");
                response.html("");
                const fileType = ".csv";
                const regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
                if (!regex.test($("#file").val().toLowerCase())) {
                    response.addClass("error");
                    response.addClass("display-block");
                    response.html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
                    return false;
                }
                setTimeout(function () {
                    location.reload();
                }, 3000);
                return true;
            });

            $("#frmGeoJsonImport").on("submit", function () {
                response1 = $("#response1");
                response1.attr("class", "");
                response1.html("");
                const fileType = ".geojson";
                const regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
                if (!regex.test($("#file2").val().toLowerCase())) {
                    response1.addClass("error");
                    response1.addClass("display-block");
                    response1.html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
                    return false;
                }
                // setTimeout(function () {
                //     location.reload();
                // },3000);
                return true;
            });

            $("#stoptimesGenerate").on("submit", function () {
                response1 = $("#response2");
                response1.attr("class", "");
                response1.html("");
                // const fileType = ".geojson";
                // const regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
                // if (!regex.test($("#file2").val().toLowerCase())) {
                //     response1.addClass("error");
                //     response1.addClass("display-block");
                //     response1.html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
                //     return false;
                // }
                // setTimeout(function () {
                //     location.reload();
                // },3000);
                return true;
            });
        });

    </script>



@stop
