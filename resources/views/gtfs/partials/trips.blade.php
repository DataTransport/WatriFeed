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

        select {
            display: initial !important;
        }

        div.select2-container {
            display: none !important;
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

    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>

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
            <div style="border: 1px solid #3f51b5;" class="panel panel-primary " data-collapsed="0">

                <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
                    <i style="color: #fff;font-size: xx-large;" class="fa fa-exchange"></i>
                </div>
                <div style="background: #3f51b5; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading"
                     class="panel-heading">
                    <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                        Trips
                    </div>

                    <div class="panel-options">
                        <span class="badge badge-success"
                              style="color: #000000; font-weight: bold; font-size: 10px">{{$trips->count()}}</span>

                        <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                        {{--                <a href="#add"  data-t="{{csrf_token()}}" data-g="{{$gtfs->id}}" data-m="{{$gtfs->password}}"  id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>--}}
                        <a href="javascript:" onclick="$('#modal-6').modal('show', {backdrop: 'static'});" id="add_"><i
                                style="color: #fff;" class="entypo-plus-circled"></i></a>

                    </div>
                </div>
                <div class="panel-body">


                    <table class="table table-bordered datatable" id="trips_data">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>ServiceID</th>
                            <th>RouteID</th>
                            <th>Headsign</th>
                            <th>Short name</th>
                            <th>DirectionID</th>
                            <th>BlockID</th>
                            <th>ShapeID</th>
                            <th>Wheelchair</th>
                            <th>BikesAllowed</th>
                            <th>A</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($trips as $trip)
                            <tr class="odd gradeX">
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="trip_id">{{$trip->trip_id}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="service_id">{{$trip->service_id}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="route_id">{{$trip->route_id}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="trip_headsign">{{$trip->trip_headsign}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="trip_short_name">{{$trip->trip_short_name}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="direction_id">{{$trip->direction_id}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="block_id">{{$trip->block_id}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="route_id">{{$trip->shape_id}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="wheelchair_accessible">{{$trip->wheelchair_accessible}}</td>
                                <td class="update {{$trip->id}}" data-id="{{$trip->id}}"
                                    data-column="bikes_allowed">{{$trip->bikes_allowed}}</td>

                                <td>
                                    <button style="display: none;" type="button" name="save_btn"
                                            class="btn btn-success btn-sm save_btn{{$trip->id}} save_btn"
                                            data-rowid="{{$trip->id}}">save
                                    </button>
                                    <button type="button" name="edit_btn"
                                            class="btn btn-info btn-sm edit_btn{{$trip->id}} edit_btn_"
                                            data-rowid="{{$trip->id}}"
                                            data-trip_id="{{$trip->trip_id}}"
                                            data-service_id="{{$trip->service_id}}"
                                            data-route_id="{{$trip->route_id}}"
                                            data-trip_headsign="{{$trip->trip_headsign}}"
                                            data-trip_short_name="{{$trip->trip_short_name}}"
                                            data-direction_id="{{$trip->direction_id}}"
                                            data-block_id="{{$trip->block_id}}"
                                            data-shape_id="{{$trip->shape_id}}"
                                            data-wheelchair_accessible="{{$trip->wheelchair_accessible}}"
                                            data-bikes_allowed="{{$trip->bikes_allowed}}"
                                    ><i class="fa fa-edit"></i></button>
                                    <button type="button" name="delete" class="btn btn-danger btn-sm delete"
                                            id="{{$trip->id}}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <th>ID</th>
                        <th>ServiceID</th>
                        <th>RouteID</th>
                        <th>Headsign</th>
                        <th>Short name</th>
                        <th>DirectionID</th>
                        <th>BlockID</th>
                        <th>ShapeID</th>
                        <th>Wheelchair</th>
                        <th>BikesAllowed</th>

                        <th></th>
                        </tfoot>
                    </table>

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

                        <input hidden type="text" id="route_id" name="route_id">
                        <input hidden type="text" id="shape_id" name="shape_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="route-id" class="control-label">Route ID <span class="field_required">*</span></label>
                                    <select class="route_id_ form-control" style="width: 100%;" id="route-id"
                                            name="route_id"></select>
                                    <strong class="errors_message route_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="service-id" class="control-label">Service ID <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="service-id" name="service_id">
                                    <strong class="errors_message service_id"
                                            style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="trip-id" class="control-label">Trip ID <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="trip-id" name="trip_id">
                                    <strong class="errors_message trip_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="trip-headsign" class="control-label">Trip Headsign</label>
                                    <input type="text" class="form-control" id="trip-headsign" name="trip_headsign">
                                </div>
                                <div class="form-group">
                                    <label for="trip-short-name" class="control-label">Trip Short Name </label><br>
                                    <input class="form-control" id="trip-short-name" name="trip_short_name">
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="direction-id" class="control-label">Direction ID</label>
                                    <input type="text" class="form-control" id="direction-id" name="direction_id">
                                </div>
                                <div class="form-group">
                                    <label for="block-id" class="control-label">Block ID</label>
                                    <input type="text" class="form-control" id="block-id" name="block_id">
                                </div>
                                <div class="form-group">
                                    <label for="shape-id" class="control-label">Shape ID</label>
                                    <select class="shape_id_ form-control" style="width: 100%;" id="shape-id"
                                            name="shape_id"></select>
                                    <strong class="errors_message shape_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="wheelchair-accessible" class="control-label">Wheelchair
                                        Accessible</label>
                                    <input type="text" class="form-control" id="wheelchair-accessible"
                                           name="wheelchair_accessible">
                                </div>
                                <div class="form-group">
                                    <label for="bikes-allowed" class="control-label">Bikes Allowed</label>
                                    <input type="text" class="form-control" id="bikes-allowed" name="bikes_allowed">
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
        $('.route_id_').select2({
            ajax: {
                url: '/select2-routes-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.route_id,
                                id: item.route_id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('.shape_id_').select2({
            ajax: {
                url: '/select2-shapes-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.shape_id,
                                id: item.shape_id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('#add_').on('click', function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Trip</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/trip/store');
            save_form();
            clear_form_modal();
        });
        $('.edit_btn_').on('click', function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Trip</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/trip/' + id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let trip_id = $(this).data('trip_id');
            let service_id = $(this).data('service_id');
            let route_id = $(this).data('route_id');
            let trip_headsign = $(this).data('trip_headsign');
            let trip_short_name = $(this).data('trip_short_name');
            let direction_id = $(this).data('direction_id');
            let block_id = $(this).data('block_id');
            let shape_id = $(this).data('shape_id');
            let wheelchair_accessible = $(this).data('wheelchair_accessible');
            let bikes_allowed = $(this).data('bikes_allowed');

            console.log(trip_id);
            $('#update_id').val(id);
            $('#trip-id').val(trip_id);
            $('#service-id').val(service_id);
            $('#route_id').val(route_id);
            $('#trip-headsign').val(trip_headsign);
            $('#trip-short-name').val(trip_short_name);
            $('#direction-id').val(direction_id);
            $('#block-id').val(block_id);
            $('#shape_id').val(shape_id);
            $('#wheelchair-accessible').val(wheelchair_accessible);
            $('#bikes-allowed').val(bikes_allowed);

            route_id = '' + route_id;
            shape_id = '' + shape_id;

            $('.route_id_').select2({
                placeholder: route_id,
                ajax: {
                    url: '/select2-stops-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.route_id,
                                    id: item.route_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.shape_id_').select2({
                placeholder: shape_id,
                ajax: {
                    url: '/select2-shapes-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.shape_id,
                                    id: item.shape_id
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
    {!! app('html')->script('dataTable/js/trips.js') !!}





@stop




