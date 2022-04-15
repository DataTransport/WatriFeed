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

        select{
            display: initial !important;
        }
        div.select2-container{
            display: none !important;
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
    <div style="border: 1px solid #005210;" class="panel panel-primary" data-collapsed="0">


        <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
            <i style="color: #fff;font-size: xx-large;" class="fa fa-money"></i>
        </div>
        <div style="background: #005210; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading">
            <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                Fare Attributes
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$fareAttributes->count()}}</span>
                <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                <a href="#add" data-t="{{csrf_token()}}" data-g="{{$gtfs->id}}" data-m="{{$gtfs->password}}" id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>
            </div>
        </div>
        <div class="panel-body">

            
            <table class="table table-bordered datatable" id="fare_attributes_data">
                <thead>
                <tr>
                    <th>FareId <span class="field_required">*</span></th>
                    <th>Price <span class="field_required">*</span></th>
                    <th>CurrencyType <span class="field_required">*</span></th>
                    <th>PaymentMethod <span class="field_required">*</span></th>
                    <th>Transfers <span class="field_required">*</span></th>
                    <th>TransferDuration</th>
                    <th>AgencyId</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($fareAttributes as $fareAttribute)
                    <tr class="odd gradeX">
                        <td class="update {{$fareAttribute->id}}" data-id="{{$fareAttribute->id}}" data-column="fare_id">{{$fareAttribute->fare_id}}</td>
                        <td class="update {{$fareAttribute->id}}" data-id="{{$fareAttribute->id}}" data-column="price">{{$fareAttribute->price}}</td>
                        <td class="update {{$fareAttribute->id}}" data-id="{{$fareAttribute->id}}" data-column="currency_type">{{$fareAttribute->currency_type}}</td>
                        <td class="update {{$fareAttribute->id}}" data-id="{{$fareAttribute->id}}" data-column="payment_method">{{$fareAttribute->payment_method}}</td>
                        <td class="update {{$fareAttribute->id}}" data-id="{{$fareAttribute->id}}" data-column="transfers">{{$fareAttribute->transfers}}</td>
                        <td class="update {{$fareAttribute->id}}" data-id="{{$fareAttribute->id}}" data-column="transfer_duration">{{$fareAttribute->transfer_duration}}</td>
                        <td>
                            <select name="" class="agencyId" data-id="{{$fareAttribute->id}}" >
                                @foreach ($agencies as $agency)
                                    <option value="{{$agency->id}}" @if ($agency->agency_id ===$fareAttribute->agency_id)
                                    selected
                                        @endif>{{$agency->agency_id}}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$fareAttribute->id}} save_btn" data-rowid="{{$fareAttribute->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$fareAttribute->id}} edit_btn" data-rowid="{{$fareAttribute->id}}"><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$fareAttribute->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>FareId</th>
                <th>Price</th>
                <th>CurrencyType</th>
                <th>PaymentMethod</th>
                <th>Transfers</th>
                <th>TransferDuration</th>
                <th>AgencyId</th>
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
    {!! app('html')->script('dataTable/js/fare_attributes.js') !!}





@stop




