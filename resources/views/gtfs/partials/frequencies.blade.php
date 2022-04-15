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
    <div style="border: 1px solid #084779;" class="panel panel-primary" data-collapsed="0">

        <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-line-chart"></i>
        </div>
        <div style="background: #084779; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class=" col-sm-8 panel-title">
                Frequencies
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$frequencies->count()}}</span>
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


            <table class="table table-bordered datatable" id="frequencies_data" data-order='[[ 4, "asc" ]]'>
                <thead>
                <tr>
                    <th>TripId</th>
                    <th>StartTime</th>
                    <th>EndTime</th>
                    <th>HeadwaySecs</th>
                    <th>ExactTime</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($frequencies as $frequency)
                    <tr class="odd gradeX">
                        <td class="update {{$frequency->id}}" data-id="{{$frequency->id}}" data-column="trip_id">{{$frequency->trip_id}}</td>
                        <td class="update {{$frequency->id}}" data-id="{{$frequency->id}}" data-column="start_time">{{$frequency->start_time}}</td>
                        <td class="update {{$frequency->id}}" data-id="{{$frequency->id}}" data-column="end_time">{{$frequency->end_time}}</td>
                        <td class="update {{$frequency->id}}" data-id="{{$frequency->id}}" data-column="headway_secs">{{$frequency->headway_secs}}</td>
                        <td class="update {{$frequency->id}}" data-id="{{$frequency->id}}" data-column="exact_times">{{$frequency->exact_times}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$frequency->id}} save_btn" data-rowid="{{$frequency->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$frequency->id}} edit_btn_" data-rowid="{{$frequency->id}}"
                                    data-trip_id="{{$frequency->trip_id}}"
                                    data-start_time="{{$frequency->start_time}}"
                                    data-end_time="{{$frequency->end_time}}"
                                    data-headway_secs="{{$frequency->headway_secs}}"
                                    data-exact_times="{{$frequency->exact_times}}"
                            ><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$frequency->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>TripId</th>
                <th>StartTime</th>
                <th>EndTime</th>
                <th>HeadwaySecs</th>
                <th>ExactTime</th>
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
                    <h4 class="modal-title" style="text-align:center;"></h4>
                </div>

                <form action="/stop/store" id="form">
                    <div class="modal-body">

                        <input hidden type="text" id="trip_id" name="trip_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="trip-id" class="control-label">Trip ID <span class="field_required">*</span></label>
                                    <select class="trip_id_ form-control" style="width: 100%;" id="trip-id" name="trip_id"></select>
                                    <strong class="errors_message trip_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="start-time" class="control-label">Start Time <span class="field_required">*</span></label>
                                    <input type="time" class="form-control" id="start-time" name="start_time">
                                    <strong class="errors_message start_time" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="end-time" class="control-label">End Time <span class="field_required">*</span></label>
                                    <input type="time" class="form-control" id="end-time" name="end_time">
                                    <strong class="errors_message end_time" style="display: none;color: red;"></strong>
                                </div>



                            </div>

                            <div class="col-md-6">

                                <div class="form-group" id="stop_lon">
                                    <label for="headway-secs" class="control-label">Headway Secs <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="headway-secs" name="headway_secs">
                                    <strong class="errors_message headway_secs" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="exact-times" class="control-label">Exact Times</label>
                                    <input type="text" class="form-control" id="exact-times" name="exact_times">
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

    <script src="/neon/js/bootstrap.js"></script>

    {!! app('html')->script('neon/js/toastr.js') !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    {!! app('html')->script('dataTable/js/functions.js') !!}
    {!! app('html')->script('dataTable/js/frequencies.js') !!}

    <script>

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
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Frequency</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/frequency/store');
            save_form();
            clear_form_modal();

        });
        $('.edit_btn_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Frequency</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/frequency/'+id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let trip_id= $(this).data('trip_id');
            let start_time= $(this).data('start_time');
            let end_time= $(this).data('end_time');
            let headway_secs= $(this).data('headway_secs');
            let exact_times= $(this).data('exact_times');



            start_time=format_time(start_time);
            end_time=format_time(end_time);

            $('#update_id').val(id);
            $('#trip_id').val(trip_id);
            $('#start-time').val(start_time);
            $('#end-time').val(end_time);
            $('#headway-secs').val(headway_secs);
            $('#exact-times').val(exact_times);


            trip_id = ''+trip_id;

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




@stop




