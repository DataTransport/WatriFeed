@extends('layouts.master')
@php
    $list_gtfs = 'active';
    $edit_ ='edit_';
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

    </style>
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
    <div style="border: 1px solid #08455f;" class="panel panel-primary" data-collapsed="0">

        <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-calendar"></i>
        </div>
        <div style="background: #08455f; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                Calendar dates
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$calendarDs->count()}}</span>
                <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                <a href="#add"  id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>
            </div>
        </div>
        <div class="panel-body">

            
            <table class="table table-bordered datatable" id="calendar_dates_data">
                <thead>
                <tr>
                    <th>ServiceID <span class="field_required">*</span></th>
                    <th>Date <span class="field_required">*</span></th>
                    <th>Exception Type <span class="field_required">*</span></th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($calendarDs as $calendarD)
                    <tr class="odd gradeX">
                        <td class="update {{$calendarD->id}}" data-id="{{$calendarD->id}}" data-column="service_id">{{$calendarD->service_id}}</td>
                        <td class="update {{$calendarD->id}}" data-id="{{$calendarD->id}}" data-column="date">{{$calendarD->date}}</td>
                        <td class="update {{$calendarD->id}}" data-id="{{$calendarD->id}}" data-column="exception_type">{{$calendarD->exception_type}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$calendarD->id}} save_btn" data-rowid="{{$calendarD->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$calendarD->id}} edit_btn" data-rowid="{{$calendarD->id}}"><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$calendarD->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>ServiceId</th>
                <th>Date</th>
                <th>Exception Type</th>
                <th></th>
                </tfoot>
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
    {!! app('html')->script('dataTable/js/calendar_date.js') !!}





@stop




