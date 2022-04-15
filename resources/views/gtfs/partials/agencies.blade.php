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

    </style>


@stop

@section('content')
    <a href="{{route('gtfs.edit', ['gtf' =>$gtfs->id ])}}" class="btn btn-primary">Back</a>
    <hr>
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

            <div style="border: 1px solid #000d58;" class="panel panel-primary " data-collapsed="0">

                <div style="background: #000d58;color: white;border: 1px solid white;" class="panel-heading">
                    <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
                        <i style="color: #fff;font-size: xx-large;" class="fa fa-home"></i>
                    </div>
                    <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                        agencies.txt
                    </div>

                    <div class="panel-options">
                        <span class="badge badge-success"
                              style="color: #000000; font-weight: bold; font-size: 10px">{{$agencies->count()}}</span>
                        <a href="#" data-rel="collapse"><i style="color: #fff;" class="entypo-down-open"></i></a>
                        <a href="#" name="add" id="add1">
                            <span style="color: #fff">insert</span>
                            <i style="color: #fff;" class="entypo-plus-circled"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">

                    <div id="alert_message"></div>

                    <table class="table table-bordered datatable table-dark" id="agencies_data">
                        <thead>
                        <tr class="replace-inputs">
                            <th>ID</th>
                            <th>Name <span class="field_required">*</span></th>
                            <th>Url <span class="field_required">*</span></th>
                            <th>Timezone <span class="field_required">*</span></th>
                            <th>Lang</th>
                            <th>Phone</th>
                            <th>Fare url</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($agencies as $agency)
                            <tr class="odd gradeX">
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_id">{{$agency->agency_id}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_name">{{$agency->agency_name}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_url">{{$agency->agency_url}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_timezone">{{$agency->agency_timezone}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_lang">{{$agency->agency_lang}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_phone">{{$agency->agency_phone}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_fare_url">{{$agency->agency_fare_url}}</td>
                                <td class="update {{$agency->id}}" data-id="{{$agency->id}}"
                                    data-column="agency_email">{{$agency->agency_email}}</td>
                                <td>
                                    <button style="display: none;" type="button" name="save_btn"
                                            class="btn btn-success btn-sm save_btn{{$agency->id}} save_btn"
                                            data-rowid="{{$agency->id}}">save
                                    </button>
                                    <button type="button" name="edit_btn"
                                            class="btn btn-info btn-sm edit_btn{{$agency->id}} edit_btn"
                                            data-rowid="{{$agency->id}}"><i class="fa fa-edit"></i></button>
                                    <button type="button" name="delete" class="btn btn-danger btn-sm delete"
                                            id="{{$agency->id}}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        @if($agencies->count()>0)
                            <tfoot>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Url</th>
                            <th>Timezone</th>
                            <th>Lang</th>
                            <th>Phone</th>
                            <th>Fare url</th>
                            <th>Email</th>
                            <th>A</th>
                            </tfoot>
                        @endif

                    </table>
                </div>
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
    {!! app('html')->script('neon/js/select2/select2.min.js') !!}
    {!! app('html')->script('neon/js/neon-chat.js') !!}
    {!! app('html')->script('neon/js/toastr.js') !!}
    {!! app('html')->script('dataTable/js/functions.js') !!}
    {!! app('html')->script('dataTable/js/agencies.js') !!}
    <script>
        $( document ).ready(function() {
            @if($agencies->count()===0)
            add_row();
            @endif
        });

    </script>
@stop




