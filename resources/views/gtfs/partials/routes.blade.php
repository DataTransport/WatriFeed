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
    <div style="border: 1px solid #004265;" class="panel panel-primary" data-collapsed="0">

        <div style="background: #004265; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading">
            <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
                <i style="color: #fff;font-size: xx-large;" class="fa fa-road"></i>
            </div>
            <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                Routes
            </div>

            <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">{{$routes->count()}}</span>

                <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                <a href="#add" data-t="{{csrf_token()}}" data-g="{{$gtfs->id}}" data-m="{{$gtfs->password}}" id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>
            </div>
        </div>
        <div class="panel-body">

            
            <table class="table table-bordered datatable" id="routes_data">
                <thead>
                <tr>
                    <th>ID <span class="field_required">*</span></th>
                    <th>Agency ID</th>
                    <th>Short Name</th>
                    <th>Long Name</th>
                    <th>Description</th>
                    <th>Type <span class="field_required">*</span></th>
                    <th>Url</th>
                    <th>Color</th>
                    <th>Text color</th>
                    <th>Sort order</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($routes as $route)
                    <tr class="odd gradeX">
                        <td class="update {{$route->id}}"  data-id="{{$route->id}}" data-column="route_id">{{$route->route_id}}</td>
                        <td>
                            <select name="" class="agencyId" data-id="{{$route->id}}" >
                                <option value="" ></option>
                                @foreach ($agencies as $agency)
                                    <option value="{{$agency->id}}" @if ($agency->agency_id ===$route->agency_id)
                                    selected
                                        @endif>{{$agency->agency_id}}</option>
                                @endforeach

                            </select>
                        </td>

                        <td class="update {{$route->id}}"  data-id="{{$route->id}}" data-column="route_short_name">{{$route->route_short_name}}</td>
                        <td class="update {{$route->id}}"  data-id="{{$route->id}}" data-column="route_long_name">{{$route->route_long_name}}</td>
                        <td class="update {{$route->id}}"  data-id="{{$route->id}}" data-column="route_desc">{{$route->route_desc}}</td>
                        {{--<td contenteditable class="update2" data-id="{{$route->id}}" data-column="route_type">{{$route->route_type}}</td>--}}
                        <td >
                            <select name="" class="routeType" data-id="{{$route->id}}">
                                <option value="0" @if ($route->route_type ==0) selected @endif>0</option>
                                <option value="1" @if ($route->route_type ==1) selected @endif>1</option>
                                <option value="2" @if ($route->route_type ==2) selected @endif>2</option>
                                <option value="3" @if ($route->route_type ==3) selected @endif>3</option>
                                <option value="4" @if ($route->route_type ==4) selected @endif>4</option>
                                <option value="5" @if ($route->route_type ==5)selected @endif>5</option>
                                <option value="6" @if ($route->route_type ==6)selected @endif>6</option>
                                <option value="7" @if ($route->route_type ==7) selected @endif>7</option>


                            </select>
                        </td>
                        <td  class="update {{$route->id}}" data-id="{{$route->id}}" data-column="route_url">{{$route->route_url}}</td>
                        <td  class="update {{$route->id}}" data-id="{{$route->id}}" data-column="route_color">{{$route->route_color}}</td>
                        <td  class="update {{$route->id}}" data-id="{{$route->id}}" data-column="route_text">{{$route->route_text}}</td>
                        <td  class="update {{$route->id}}" data-id="{{$route->id}}" data-column="route_sort_order">{{$route->route_sort_order}}</td>

                        <td>
                            <button style="display: none;" type="button" name="save_btn" class="btn btn-success btn-sm save_btn{{$route->id}} save_btn" data-rowid="{{$route->id}}">save</button>
                            <button type="button" name="edit_btn" class="btn btn-info btn-sm edit_btn{{$route->id}} edit_btn" data-rowid="{{$route->id}}"><i class="fa fa-edit"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="{{$route->id}}"><i class="fa fa-trash"></i></button>
                            <a href="/route-visualisation?id={{$route->id}}" type="button" name="details" class="btn btn-default btn-sm detail"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <th>ID</th>
                <th>Agency ID</th>
                <th>Short Name</th>
                <th>Long Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Url</th>
                <th>Color</th>
                <th>Text color</th>
                <th>Sort order</th>
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
    {!! app('html')->script('dataTable/js/routes.js') !!}



<script>

 $(document).on('click', '.delete', function(){
        const id = $(this).attr("id");
        if(confirm("Are you sure you want to remove this?")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:ConfigJs.pre_url+id+"/delete",
                method:"POST",
                data:{id:id},
                success:function(data){
                    messageFlash(data,'success');
                    $('#'+ConfigJs.fetch_id).DataTable().destroy();
                    fetch_data();
                }
            });
            setInterval(function(){
                location.reload();
            }, 2000);
        }
    });

 $('.edit_btn').click(function () {
        let rowid = $(this).data('rowid');
        let fields_contenteditable = $('.'+rowid);
        let save_btn = $('.save_btn'+rowid);
        fields_contenteditable.attr('contenteditable','true');
        fields_contenteditable.css('border','blue 1px solid');
        save_btn.css('display','inline');
        $(this).hide();

    });
    $('.save_btn').click(function () {
        let rowid = $(this).data('rowid');
        let fields_contenteditable = $('.'+rowid);
        fields_contenteditable.attr('contenteditable','false');
        fields_contenteditable.css('border','#ebebeb 1px solid');
        $('.edit_btn'+rowid).show();
        $(this).hide();

    })


</script>
@stop




