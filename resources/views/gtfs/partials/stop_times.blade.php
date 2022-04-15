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
        .select2-arrow{
            width: 10px !important;
        }
        .toast-message{
            width: 500px;
        }
        td{
            border: 2px solid black;
            color: #000;
        }
        .btn-block{
            font-size: large;
            font-weight: bold;
        }
        select{
            display: initial !important;
        }
        div.select2-container{
            display: none !important;
        }

        .select2-dropdown{
            z-index: 20001 !important;
        }
        .border_red{
            border: 1px red solid !important;
        }
        .select2-selection__placeholder{
            color: #000 !important;
        }

    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

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
    <div style="border: 1px solid #009689;" class="panel panel-primary" data-collapsed="0">

        <div class="col-sm-1" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-clock-o"></i>
        </div>
        <div style="background: #009689; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                <div class="row">

                    <div class="col-sm-2">
                        Stop Times
                    </div>

                    <div class="col-sm-3">
                        <form action="/stoptime" method="get">
                            <input type="text" hidden value="{{csrf_token()}}" name="t">
                            <input type="text" hidden value="{{$gtfs->id}}" name="g">
                            <input type="text" hidden value="{{$gtfs->password}}" name="_">
                            <div class="input-group" style="width: 200px;">
                                <input type="text" class="form-control" name="search" placeholder="StopID">
                                <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><i style="color:#fff;" class="entypo-search"></i> </button>
                            </span>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3">
                        <form action="/stoptime" method="get">
                            <input type="text" hidden value="{{csrf_token()}}" name="t">
                            <input type="text" hidden value="{{$gtfs->id}}" name="g">
                            <input type="text" hidden value="{{$gtfs->password}}" name="_">
                            <div class="input-group" style="width: 200px;">
                                <input type="text" class="form-control" name="search_trip" placeholder="TripID">
                                <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><i style="color:#fff;" class="entypo-search"></i> </button>
                            </span>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3">
                        <form action="/delete-stoptimes" method="post">
                            @csrf
                            {{--                                    <input type="text" hidden value="{{csrf_token()}}" name="t">--}}
                            <input type="text" hidden value="{{$gtfs->id}}" name="g">
                            <div class="input-group" style="width: 200px;">
                                <input type="text" class="form-control" name="id" placeholder="TripID" required>
                                <span class="input-group-btn">
                                        <button class="btn btn-danger"
                                                onclick="return confirm('Are you sure you would like to delete these StopTimes');"
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
                    <?php
                        if (isset($stopTs) and !empty($stopTs)):
                    ?>
                     Records {{ $stopTs->firstItem() }} - {{ $stopTs->lastItem() }} of {{ $stopTs->total() }} (for page {{ $stopTs->currentPage() }} )
                    <?php
                        endif;
                        ?>

                </span>

                <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                <a href="javascript:" onclick="$('#modal-6').modal('show', {backdrop: 'static'});" id="add_"><i style="color: #fff;" class="entypo-plus-circled"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <div class="errors_row" style="
                text-align: center;
                color: red;
                font-weight: bold;
                border: 1px solid;
                margin-bottom: 15px;
                display: none;">

            </div>
            <table class="table table-bordered datatable" id="stop_times_data" data-order='[[ 1, "asc" ]]'>
                <thead>
                <tr>
                    <th>TripID</th>
                    <th>ArrivalTime</th>
                    <th>DepartureTime</th>
                    <th>StopID</th>
                    <th>Sequence</th>
                    <th>Headsign</th>
                    <th>PickupType</th>
                    <th>DropOffType</th>
                    <th>ShapeDistTraveled</th>
                    <th>Timepoint</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($stopTs as $stopT)
                    <tr class="odd gradeX">
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="trip_id">{{$stopT->trip_id}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="arrival_time">{{$stopT->arrival_time}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="departure_time">{{$stopT->departure_time}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="stop_id">{{$stopT->stop_id}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="stop_sequence">{{$stopT->stop_sequence}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="stop_headsign">{{$stopT->stop_headsign}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="pickup_type">{{$stopT->pickup_type}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="drop_off_type">{{$stopT->drop_off_type}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="shape_dist_traveled">{{$stopT->shape_dist_traveled}}</td>
                        <td class="update {{$stopT->id}}" data-id="{{$stopT->id}}" data-column="timepoint">{{$stopT->timepoint}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$stopT->id}} save_btn" data-rowid="{{$stopT->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$stopT->id}} edit_btn_" data-rowid="{{$stopT->id}}"
                                    data-trip_id="{{$stopT->trip_id}}"
                                    data-arrival_time="{{$stopT->arrival_time}}"
                                    data-departure_time="{{$stopT->departure_time}}"
                                    data-stop_id="{{$stopT->stop_id}}"
                                    data-stop_sequence="{{$stopT->stop_sequence}}"
                                    data-stop_headsign="{{$stopT->stop_headsign}}"
                                    data-pickup_type="{{$stopT->pickup_type}}"
                                    data-drop_off_type="{{$stopT->drop_off_type}}"
                                    data-shape_dist_traveled="{{$stopT->shape_dist_traveled}}"
                                    data-timepoint="{{$stopT->timepoint}}"
                            ><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$stopT->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>ID</th>
                <th>ArrivalTime</th>
                <th>DepartureTime</th>
                <th>StopID</th>
                <th>Sequence</th>
                <th>Headsign</th>
                <th>PickupType</th>
                <th>DropOffType</th>
                <th>ShapeDistTraveled</th>
                <th>timepoint</th>
                <th></th>
                </tfoot>
            </table>

            <?php
            if (isset($stopTs) and !empty($stopTs)):
                ?>
            {{ $stopTs->links() }}

            <?php endif ?>
        </div>
    </div>
</div>
    </div>

    <!-- Modal 6 (Long Modal)-->
    <div class="modal fade" id="modal-6">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="text-align: center"></h4>
                </div>

                <form action="/stop/store" id="form">
                    <div class="modal-body">

                        <input hidden type="text" id="trip_id" name="trip_id">
                        <input hidden type="text" id="stop_id" name="stop_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="trip-id" class="control-label">Trip ID <span class="field_required">*</span></label>
                                    <select class="trip_id_ form-control" style="width: 100%;" id="trip-id" name="trip_id"></select>
                                    <strong class="errors_message trip_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="arrival-time" class="control-label">Arrival Time</label>
                                    <input type="time" class="form-control" id="arrival-time" name="arrival_time">
                                    <strong class="errors_message arrival_time" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="departure-time" class="control-label">Departure Time</label>
                                    <input type="time" class="form-control" id="departure-time" name="departure_time">
                                    <strong class="errors_message departure_time" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="stop-id" class="control-label">Stop ID <span class="field_required">*</span></label>
                                    <select class="stop_id_ form-control" style="width: 100%;" id="stop-id" name="stop_id"></select>
                                    <strong class="errors_message stop_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="stop-sequence" class="control-label">Stop Sequence <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="stop-sequence" name="stop_sequence">
                                    <strong class="errors_message stop_sequence" style="display: none;color: red;"></strong>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="stop-headsign" class="control-label">Stop Headsign</label>
                                    <input type="text" class="form-control" id="stop-headsign" name="stop_headsign">
                                </div>
                                <div class="form-group">
                                    <label for="pickup-type" class="control-label">Pickup Type </label><br>
                                    <input class="form-control" id="pickup-type" name="pickup_type">
                                </div>
                                <div class="form-group">
                                    <label for="drop-off-type" class="control-label">Drop Off Type </label><br>
                                    <input class="form-control" id="drop-off-type" name="drop_off_type">
                                </div>
                                <div class="form-group">
                                    <label for="shape-dist-traveled" class="control-label">Shape Dist Traveled </label><br>
                                    <input class="form-control" id="shape-dist-traveled" name="shape_dist_traveled">
                                </div>
                                <div class="form-group">
                                    <label for="timepoint" class="control-label">Timepoint </label><br>
                                    <input class="form-control" id="timepoint" name="timepoint">
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>

            </div>
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
    {!! app('html')->script('neon/js/toastr.js') !!}

    <script src="/neon/js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script>
        $('.stop_id_').select2({
            ajax: {
                url: '/select2-stops-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.stop_id,
                                id: item.stop_id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('.trip_id_').select2({
            ajax: {
                url: '/select2-trips-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.trip_id,
                                id: item.trip_id
                            }
                        })
                    };
                },
                cache: true
            }
        });


        $('#add_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Stop Times</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/stoptime/store');
            save_form();
            clear_form_modal();

        });
        $('.edit_btn_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Stop Times</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/stoptime/'+id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let trip_id= $(this).data('trip_id');
            let arrival_time= $(this).data('arrival_time');
            let departure_time= $(this).data('departure_time');
            let stop_id= $(this).data('stop_id');
            let stop_sequence= $(this).data('stop_sequence');
            let stop_headsign= $(this).data('stop_headsign');
            let pickup_type= $(this).data('pickup_type');
            let drop_off_type= $(this).data('drop_off_type');
            let shape_dist_traveled= $(this).data('shape_dist_traveled');
            let timepoint= $(this).data('timepoint');


            arrival_time=format_time(arrival_time);
            departure_time=format_time(departure_time);

            $('#update_id').val(id);
            $('#trip_id').val(trip_id);
            $('#arrival-time').val(arrival_time);
            $('#departure-time').val(departure_time);
            $('#stop_id').val(stop_id);
            $('#stop-sequence').val(stop_sequence);
            $('#stop-headsign').val(stop_headsign);
            $('#pickup-type').val(pickup_type);
            $('#drop-off-type').val(drop_off_type);
            $('#shape-dist-traveled').val(shape_dist_traveled);
            $('#timepoint').val(timepoint);

            trip_id = ''+trip_id;
            stop_id = ''+stop_id;

            $('.stop_id_').select2({
                placeholder: stop_id,
                ajax: {
                    url: '/select2-stops-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.stop_id,
                                    id: item.stop_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.trip_id_').select2({
                placeholder: trip_id,
                ajax: {
                    url: '/select2-trips-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.trip_id,
                                    id: item.trip_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>

    {!! app('html')->script('dataTable/js/functions.js') !!}
    {!! app('html')->script('dataTable/js/stop_times.js') !!}
@stop

