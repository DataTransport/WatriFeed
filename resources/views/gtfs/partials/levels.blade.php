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
    <div style="border: 1px solid #3c1f6f;" class="panel panel-primary " data-collapsed="0">

        <div class="col-sm-1" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-sort-numeric-desc"></i>
        </div>
        <div style="background: #3c1f6f; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading" class="panel-heading">

            <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                levles.txt
            </div>


                <div class="panel-options">

                    <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                    <a href="#add" id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>

                </div>


        </div>
        <div class="panel-body" style="">

            <table class="table table-bordered datatable" id="levels_data">
                <thead>
                <tr>
                    <th>ID <span class="field_required">*</span></th>
                    <th>Index <span class="field_required">*</span></th>
                    <th>Name</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($levels as $level)
                    <tr class="odd gradeX">
                        <td class="update {{$level->id}}" data-id="{{$level->id}}" data-column="level_id">{{$level->level_id}}</td>
                        <td class="update {{$level->id}}" data-id="{{$level->id}}" data-column="level_index">{{$level->level_index}}</td>
                        <td class="update {{$level->id}}" data-id="{{$level->id}}" data-column="level_name">{{$level->level_name}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$level->id}} save_btn" data-rowid="{{$level->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$level->id}} edit_btn" data-rowid="{{$level->id}}"><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$level->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>ID</th>
                <th>Index</th>
                <th>Name</th>
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
    {!! app('html')->script('dataTable/js/levels.js') !!}





@stop




