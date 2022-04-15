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
            <i style="color: #fff;font-size: xx-large;" class="fa fa-external-link-square"></i>
        </div>
        <div style="background: #084779; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class=" col-sm-8 panel-title">
                pathways.txt
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$pathways->count()}}</span>
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
                    <th>ID</th>
                    <th>fromStopId</th>
                    <th>toStopId</th>
                    <th>pathwayMode</th>
                    <th>is_bidirectional</th>
                    <th>length</th>
                    <th>traversalTime</th>
                    <th>stairCount</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($pathways as $pathway)
                    <tr class="odd gradeX">
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="pathway_id">{{$pathway->pathway_id}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="from_stop_id">{{$pathway->from_stop_id}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="to_stop_id">{{$pathway->to_stop_id}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="pathway_mode">{{$pathway->pathway_mode}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="is_bidirectional">{{$pathway->is_bidirectional}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="length">{{$pathway->length}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="traversal_time">{{$pathway->traversal_time}}</td>
                        <td class="update {{$pathway->id}}" data-id="{{$pathway->id}}" data-column="stair_count">{{$pathway->stair_count}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$pathway->id}} save_btn" data-rowid="{{$pathway->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$pathway->id}} edit_btn_" data-rowid="{{$pathway->id}}"
                                    data-pathway_id="{{$pathway->pathway_id}}"
                                    data-from_stop_id="{{$pathway->from_stop_id}}"
                                    data-to_stop_id="{{$pathway->to_stop_id}}"
                                    data-pathway_mode="{{$pathway->pathway_mode}}"
                                    data-is_bidirectional="{{$pathway->is_bidirectional}}"
                                    data-length="{{$pathway->length}}"
                                    data-traversal_time="{{$pathway->traversal_time}}"
                                    data-stair_count="{{$pathway->stair_count}}"
                                    data-max_slope="{{$pathway->max_slope}}"
                                    data-min_width="{{$pathway->min_width}}"
                                    data-signposted_as="{{$pathway->signposted_as}}"
                                    data-reversed_signposted_as="{{$pathway->reversed_signposted_as}}"
                            ><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$pathway->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>ID</th>
                <th>fromStopId</th>
                <th>toStopId</th>
                <th>pathwayMode</th>
                <th>is_bidirectional</th>
                <th>length</th>
                <th>traversalTime</th>
                <th>stairCount</th>
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
                                    <label for="pathway-id" class="control-label">Pathway id <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="pathway-id" name="pathway_id">
                                    <strong class="errors_message pathway_id" style="display: none;color: red;"></strong>
                                </div>
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
                                <div class="form-group">
                                    <label for="pathway-mode" class="control-label">Pathway Mode <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="pathway-mode" name="pathway_mode">
                                    <strong class="errors_message pathway_mode" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="is-bidirectional" class="control-label">Is Bidirectional <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" id="is-bidirectional" name="is_bidirectional">
                                    <strong class="errors_message is_bidirectional" style="display: none;color: red;"></strong>
                                </div>

                                <div class="form-group">
                                    <label for="length" class="control-label">Length</label>
                                    <input type="text" class="form-control" id="length" name="length">
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group" id="stop_lon">
                                    <label for="traversal-time" class="control-label">Travelsal Time</label>
                                    <input type="number" class="form-control" id="traversal-time" name="traversal_time">
                                </div>

                                <div class="form-group" id="stop_lon">
                                    <label for="stair-count" class="control-label">Stair Count</label>
                                    <input type="number" class="form-control" id="stair-count" name="stair_count">
                                </div>

                                <div class="form-group">
                                    <label for="max-slope" class="control-label">Max Slope</label>
                                    <input type="text" class="form-control" id="max-slope" name="max_slope">
                                </div>

                                <div class="form-group">
                                    <label for="min-width" class="control-label">Min Width</label>
                                    <input type="text" class="form-control" id="min-width" name="min_width">
                                </div>

                                <div class="form-group">
                                    <label for="signposted-as" class="control-label">Signposted as</label>
                                    <input type="text" class="form-control" id="signposted-as" name="signposted_as">
                                </div>

                                <div class="form-group">
                                    <label for="reversed-signposted_as" class="control-label">Reversed Signposted as</label>
                                    <input type="text" class="form-control" id="reversed-signposted_as" name="reversed_signposted_as">
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
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Pathways</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/pathway');
            save_form();
            clear_form_modal();

        });
        $('.edit_btn_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Pathways</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/pathway/'+id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let pathway_id= $(this).data('pathway_id');
            let from_stop_id= $(this).data('from_stop_id');
            let to_stop_id= $(this).data('to_stop_id');
            let pathway_mode= $(this).data('pathway_mode');
            let is_bidirectional= $(this).data('is_bidirectional');
            let length= $(this).data('length');
            let traversal_time= $(this).data('traversal_time');
            let stair_count= $(this).data('stair_count');
            let max_slope= $(this).data('max_slope');
            let min_width= $(this).data('min_width');
            let signposted_as= $(this).data('signposted_as');
            let reversed_signposted_as= $(this).data('reversed_signposted_as');



            // start_time=format_time(start_time);


            $('#update_id').val(id);
            $('#pathway-id').val(pathway_id);
            $('#from_stop_id').val(from_stop_id);
            $('#to_stop_id').val(to_stop_id);
            $('#pathway-mode').val(pathway_mode);
            $('#is-bidirectional').val(is_bidirectional);
            $('#length').val(length);
            $('#traversal-time').val(traversal_time);
            $('#stair-count').val(stair_count);
            $('#max-slope').val(max_slope);
            $('#min-width').val(min_width);
            $('#signposted-as').val(signposted_as);
            $('#reversed-signposted_as').val(reversed_signposted_as);


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




