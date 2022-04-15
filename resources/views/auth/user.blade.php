@extends('layouts.master')
@php
    $list_users = 'active';
@endphp
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
        <div style="border: 1px solid black;" class="panel panel-primary" data-collapsed="0">

            <div style="border-bottom: 1px solid #000000;" class="panel-heading">
                <div style="font-weight: bold;font-size: 18px; text-align: center"
                     class="col-sm-offset-2 col-sm-8 panel-title">
                    Users List
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered datatable col-sm-12" id="table-4">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Key</th>
                        <th>Etat</th>
                        <th>Creation Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=1;
                    @endphp
                    @foreach ($users as $user)

                        <tr class="odd gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td> {{$user->name}}</td>
                            <td> {{$user->email}}</td>
                            <td class="center">{{$user->key_api}}</td>
                            <td class="center">
                                @if ($user->state)
                                    <i class="entypo-check" style="color: #00a000"></i>
                                @else
                                    <i class="entypo-cancel" style="color: #d90900"></i>
                                @endif
                            </td>
                            <td class="center">{{$user->created_at}}</td>
                            <td>
                                @if ((int)$user->id!==7 && (int)$user->id!==8 )
                                    <a data-name="{{$user->name}}" class="edit_gtfs btn btn-info btn-sm btn-icon icon-left"
                                       href="{{url('reset_user',['id'=>$user->id])}}">
                                        <i class="entypo-arrows-ccw"></i>
                                        Reset Key
                                    </a>
                                @endif

                                @if (!$user->state)
                                    <a href="{{ url('active_user',['id'=>$user->id]) }}"
                                       class="btn btn-success btn-sm btn-icon icon-left">
                                        <i class="entypo-check"></i>
                                        Actived
                                    </a>
                                @else
                                    <a href="{{ url('active_user',['id'=>$user->id]) }}"
                                       class="btn btn-danger btn-sm btn-icon icon-left">
                                        <i class="entypo-cancel"></i>
                                        Disabled
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal 6 (Long Modal)-->
    <div class="modal fade" id="modal-6">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Password</h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">

                            <form method="POST" action="{{ route('gtfs.check_pass.post') }} " id="form">
                                @csrf
                                <div class="row">

                                    <div style="padding-left: 25px;"
                                         class="div_pass form-group  col-md-12 {!! $errors->has('password') ? 'has-error' : '' !!}">
                                        {!! Form::password('password',['class' => 'form-control', 'placeholder' => 'GTFS-Password' ]) !!}
                                    </div>
                                    <input type="text" name="name" hidden>

                                </div>

                                {!! Form::submit('Submit', ['class' => 'btn_s btn btn-primary pull-right']) !!}
                                <span id="loading" style="display: none">loading...</span>
                            </form>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{--                    <button type="button" class="btn btn-info">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts_page')


@stop
