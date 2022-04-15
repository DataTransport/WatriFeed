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
            text-align: center;
            font-weight: bold;
        }

        .route_title {
            font-weight: bold;
            font-size: 15px !important;
        }
    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>

    {{--    mapping--}}

@stop

@section('content')
    <a href="/route" class="btn btn-primary">Back</a>
    <br>
    @php
        $type = ['','','','Bus'];
    @endphp



    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-gradient" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title"><h3>Route informations</h3></div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                <div>

                    <p class="route_title">Agency {{$route->agency_id}}</p>
                    <hr class="watri_hr">
                    <p class="route_title">ID {{$route->route_id}}</p>
                    <hr class="watri_hr">
                    <p class="route_title">Short Name {{$route->route_short_name}}</p>
                    <hr class="watri_hr">
                    <p class="route_title">Long Name {{$route->route_long_name}}</p>
                    <hr class="watri_hr">
                    <p class="route_title">Description {{$route->route_desc===""?'empty':$route->route_desc}}</p>
                    <hr class="watri_hr">
                    <p class="route_title">URL {{$route->route_url===""?'empty':$route->route_url}}</p>
                    <hr class="watri_hr">
                    @if(isset($type[$route->route_type]))
                    <p class="route_title">Type {{$type[$route->route_type]}}</p>
                    @endif
                    <hr class="watri_hr">
                    <div><span class="route_title">Color</span>
                        <div
                            style="background-color: #{{$route->route_color}};height: 1em;width: 1em;display: inline-block;border: 1px solid black; "></div> {{$route->route_color}}
                    </div>
                    <hr class="watri_hr">
                    <div><span class="route_title">Text Color</span>
                        <div
                            style="background-color: #{{$route->route_text_color}};height: 1em;width: 1em;display: inline-block;border: 1px solid black; "></div> {{$route->route_text_color}}
                    </div>
                    <hr class="watri_hr">

                </div>
            </div>

        </div>
    </div>



    <div>
        <table class="table table-dark table-striped table-bordered " style="width: 100%;">
            @php
                use App\helpers\WatriHelper;
            @endphp
            <thead class="thead-dark" style="font-size: 18px ">
            <tr>
                <th scope="col">Group</th>
                <th scope="col">Trip</th>
                <th scope="col">Days Active</th>
                <th scope="col">Times Covered ()</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trips as $trip)
                @if ($trip->stops)

                    @php
                        $array_stop = $trip->stops;
                        $first_stop = $trip->stops[0];
                        $last_stop = end($array_stop);

                        $frequency = \App\Frequency::where('trip_id',$trip->trip_id)
                        ->where('gtfs_id', session('gtfs_id'))->first();
                        if($frequency){
                            $start_time = new DateTime((string)$frequency->start_time);
                        $end_time = new DateTime((string)$frequency->end_time);
                        $interval = $start_time->diff($end_time);
                        $elapsed = $interval->format('%Hh%im');

                        }


                      //  $tripStart_time = new DateTime((string)$trip->stoptimes->first()->arrival_time);

                        //$tripEnd_time = new DateTime((string)$trip->stoptimes->last()->departure_time);
                        //$tripEnterval = $tripStart_time->diff($tripEnd_time);
                        //$tripElapsed = $tripEnterval->format('%Hh%im');
                       // echo $tripElapsed;

                    @endphp
{{--                    @dd('')--}}
                    <tr scope="row">
                        <td>To {{$first_stop->stop_name}} From {{$last_stop->stop_name}} ({{ count($trip->stops) }}
                            stops)
                        </td>
                        <td>
                            <a class="btn-block" href="/trip-visualisation?route={{$route->id}}&trip={{$trip->id}}">
                                {{$first_stop->stop_name}} --> {{$last_stop->stop_name}}
                            </a>
                        </td>
                        <td>{{WatriHelper::serviceIdToString($trip->service_id)}}</td>
                        <td>
{{--                            @if ($frequency)--}}
{{--                                <div>--}}
{{--                                    <div class="timebar inexact"--}}
{{--                                         title="{{$frequency->start_time}} - {{$frequency->end_time}} (26m) ~69 trips every {{round((int)$frequency->headway_secs/60)}}m"--}}
{{--                                         style="width: 99.619%; left: 0%;">--}}
{{--                                        <div class="trail" style="margin-left: 97.5143%;"></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}

                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>

        </table>
    </div>

@endsection




@section('styles_page')
    {!! app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css') !!}

@stop

@section('scripts_page')
    {{--    {!! app('html')->script('neon/js/neon-chat.js') !!}--}}

    {{--    <script src="/neon/js/bootstrap.js"></script>--}}

    {!! app('html')->script('neon/js/toastr.js') !!}



@stop

