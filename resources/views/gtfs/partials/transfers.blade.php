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
    <div style="border: 1px solid #7d0830;" class="panel panel-primary" data-collapsed="0">

        <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-exchange"></i>
        </div>
        <div style="background: #7d0830; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class=" col-sm-8 panel-title">
                Transfers
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$transfers->count()}}</span>

                <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                <a href="javascript:" onclick="$('#modal-6').modal('show', {backdrop: 'static'});" id="add_"><i style="color: #fff;" class="entypo-plus-circled"></i></a>
            </div>
        </div>
        <div class="panel-body">

           
            <table class="table table-bordered datatable" id="transfers_data">
                <thead>
                <tr>
                    <th>From Stop Id</th>
                    <th>To Stop Id</th>
                    <th>Transfer Type</th>
                    <th>Min Transfer Time</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($transfers as $transfer)
                    <tr class="odd gradeX">

                        <td class="update {{$transfer->id}}" data-id="{{$transfer->id}}" data-column="from_stop_id">{{$transfer->from_stop_id}}</td>
                        <td class="update {{$transfer->id}}" data-id="{{$transfer->id}}" data-column="to_stop_id">{{$transfer->to_stop_id}}</td>
                        <td class="update {{$transfer->id}}" data-id="{{$transfer->id}}" data-column="transfer_type">{{$transfer->transfer_type}}</td>
                        <td class="update {{$transfer->id}}" data-id="{{$transfer->id}}" data-column="min_transfer_time">{{$transfer->min_transfer_time}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$transfer->id}} save_btn" data-rowid="{{$transfer->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$transfer->id}} edit_btn_" data-rowid="{{$transfer->id}}"

                                    data-from_stop_id="{{$transfer->from_stop_id}}"
                                    data-to_stop_id="{{$transfer->to_stop_id}}"
                                    data-transfer_type="{{$transfer->transfer_type}}"
                                    data-min_transfer_time="{{$transfer->min_transfer_time}}"

                            ><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$transfer->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>From Stop Id</th>
                <th>To Stop Id</th>
                <th>Transfer Type</th>
                <th>Min Transfer Time</th>
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

                <form action="" id="form">
                    <div class="modal-body">

                        <input hidden type="text" id="from_stop_id" name="from_stop_id">
                        <input hidden type="text" id="to_stop_id" name="to_stop_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from-stop-id" class="control-label">From Stop ID <span class="field_required">*</span></label>
                                    <select class="from_stop_id_ form-control" style="width: 100%;" id="from-stop-id" name="from_stop_id"></select>
                                    <strong class="errors_message from_stop_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="to-stop-id" class="control-label">To Stop ID <span class="field_required">*</span></label>
                                    <select class="to_stop_id_ form-control" style="width: 100%;" id="to-stop-id" name="to_stop_id"></select>
                                    <strong class="errors_message to_stop_id" style="display: none;color: red;"></strong>
                                </div>



                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transfer-type" class="control-label">Transfer Type <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="transfer-type" name="transfer_type">
                                    <strong class="errors_message transfer_type" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="min-transfer_time" class="control-label">Min Transfer Time</label>
                                    <input type="text" class="form-control" id="min-transfer_time" name="min_transfer_time">
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
    {!! app('html')->script('dataTable/js/transfers.js') !!}


    <script>

        $('.from_stop_id_').select2({
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
        $('.to_stop_id_').select2({
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


        $('#add_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Transfer</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/transfer');
            save_form();
            clear_form_modal();

        });
        $('.edit_btn_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Transfer</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/transfer/'+id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let from_stop_id= $(this).data('from_stop_id');
            let to_stop_id= $(this).data('to_stop_id');
            let transfer_type= $(this).data('transfer_type');
            let min_transfer_time= $(this).data('min_transfer_time');




            // start_time=format_time(start_time);


            $('#update_id').val(id);
            $('#from_stop_id').val(from_stop_id);
            $('#to_stop_id').val(to_stop_id);
            $('#transfer-type').val(transfer_type);
            $('#min-transfer_time').val(min_transfer_time);



            from_stop_id = ''+from_stop_id;
            to_stop_id = ''+to_stop_id;

            $('.from_stop_id_').select2({
                placeholder: from_stop_id,
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
            $('.to_stop_id_').select2({
                placeholder: to_stop_id,
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
        });
    </script>



@stop




