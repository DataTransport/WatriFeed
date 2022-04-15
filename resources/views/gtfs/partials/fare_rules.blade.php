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
    <div style="border: 1px solid #4c2618;" class="panel panel-primary" data-collapsed="0">

        <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-chain"></i>
        </div>
        <div style="background: #4c2618; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class=" col-sm-8 panel-title">
                Fare Rules
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$fareRules->count()}}</span>
                <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                <a href="javascript:" onclick="$('#modal-6').modal('show', {backdrop: 'static'});" id="add_"><i style="color: #fff;" class="entypo-plus-circled"></i></a>
            </div>
        </div>
        <div class="panel-body">

            <table class="table table-bordered datatable" id="fare_rules_data" data-order='[[ 5, "asc" ]]'>
                <thead>
                <tr>
                    <th>FareId</th>
                    <th>RouteId</th>
                    <th>OriginId</th>
                    <th>Destination</th>
                    <th>ContainsId</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($fareRules as $fareRule)
                    <tr class="odd gradeX">
                        <td class="update {{$fareRule->id}}" data-id="{{$fareRule->id}}" data-column="fare_id">{{$fareRule->fare_id}}</td>
                        <td class="update {{$fareRule->id}}" data-id="{{$fareRule->id}}" data-column="route_id">{{$fareRule->route_id}}</td>
                        <td class="update {{$fareRule->id}}" data-id="{{$fareRule->id}}" data-column="origin_id">{{$fareRule->origin_id}}</td>
                        <td class="update {{$fareRule->id}}" data-id="{{$fareRule->id}}" data-column="destination_id">{{$fareRule->destination_id}}</td>
                        <td class="update {{$fareRule->id}}" data-id="{{$fareRule->id}}" data-column="contains_id">{{$fareRule->contains_id}}</td>



                        <td>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$fareRule->id}} edit_btn_" data-rowid="{{$fareRule->id}}"
                                    data-fare_id="{{$fareRule->fare_id}}"
                                    data-route_id="{{$fareRule->route_id}}"
                                    data-origin_id="{{$fareRule->origin_id}}"
                                    data-destination_id="{{$fareRule->destination_id}}"
                                    data-contains_id="{{$fareRule->contains_id}}"
                            ><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$fareRule->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>FareId</th>
                <th>RouteId</th>
                <th>OriginId</th>
                <th>Destination</th>
                <th>ContainsId</th>
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

                <form action="" id="form">
                    <div class="modal-body">

                        <input hidden type="text" id="fare_id" name="fare_id">
                        <input hidden type="text" id="route_id" name="route_id">
                        <input hidden type="text" id="origin_id" name="origin_id">
                        <input hidden type="text" id="destination_id" name="destination_id">
                        <input hidden type="text" id="contains_id" name="contains_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fare-id" class="control-label">Fare ID <span class="field_required">*</span></label>
                                    <select class="fare_id_ form-control" style="width: 100%;" id="fare-id" name="fare_id"></select>
                                    <strong class="errors_message fare_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="route-id" class="control-label">Route ID</label>
                                    <select class="route_id_ form-control" style="width: 100%;" id="route-id" name="route_id"></select>
                                    <strong class="errors_message route_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="contains-id" class="control-label">Contains ID</label>
                                    <select class="contains_id_ form-control" style="width: 100%;" id="contains-id" name="contains_id"></select>
                                    <strong class="errors_message contains_id" style="display: none;color: red;"></strong>
                                </div>



                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="origin-id" class="control-label">Origin ID</label>
                                    <select class="origin_id_ form-control" style="width: 100%;" id="origin-id" name="origin_id"></select>
                                    <strong class="errors_message origin_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="destination-id" class="control-label">Destination ID</label>
                                    <select class="destination_id_ form-control" style="width: 100%;" id="destination-id" name="destination_id"></select>
                                    <strong class="errors_message destination_id" style="display: none;color: red;"></strong>
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
        $('.fare_id_').select2({
            ajax: {
                url: '/select2-fares-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.fare_id,
                                id: item.fare_id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('.route_id_').select2({
            ajax: {
                url: '/select2-routes-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
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
        $('.origin_id_').select2({
            ajax: {
                url: '/select2-zones-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.zone_id,
                                id: item.zone_id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('.destination_id_').select2({
            ajax: {
                url: '/select2-zones-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.zone_id,
                                id: item.zone_id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('.contains_id_').select2({
            ajax: {
                url: '/select2-zones-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.zone_id,
                                id: item.zone_id
                            }
                        })
                    };
                },
                cache: true
            }
        });


        $('#add_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Stop</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/farerule/store');
            save_form();
            clear_form_modal();

        });
        $('.edit_btn_').on('click',function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Stop</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/farerule/'+id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let fare_id= $(this).data('fare_id');
            let route_id= $(this).data('route_id');
            let origin_id= $(this).data('origin_id');
            let destination_id= $(this).data('destination_id');
            let contains_id= $(this).data('contains_id');


            $('#update_id').val(id);
            $('#fare_id').val(fare_id);
            $('#route_id').val(route_id);
            $('#origin_id').val(origin_id);
            $('#destination_id').val(destination_id);
            $('#contains_id').val(contains_id);


            fare_id = ''+fare_id;
            route_id = ''+route_id;
            origin_id = ''+origin_id;
            destination_id = ''+destination_id;
            contains_id = ''+contains_id;

            $('.fare_id_').select2({
                placeholder: fare_id,
                ajax: {
                    url: '/select2-fares-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.fare_id,
                                    id: item.fare_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.route_id_').select2({
                placeholder: route_id,
                ajax: {
                    url: '/select2-routes-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
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
            $('.origin_id_').select2({
                placeholder: origin_id,
                ajax: {
                    url: '/select2-zones-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.zone_id,
                                    id: item.zone_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.destination_id_').select2({
                placeholder: destination_id,
                ajax: {
                    url: '/select2-zones-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.zone_id,
                                    id: item.zone_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.contains_id_').select2({
                placeholder: contains_id,
                ajax: {
                    url: '/select2-zones-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.zone_id,
                                    id: item.zone_id
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
    {!! app('html')->script('dataTable/js/fare_rules.js') !!}
@stop




