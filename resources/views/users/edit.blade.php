@extends('layouts.master')

@section('content')
    <div class=" col-md-12">

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
                <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-offset-2 col-sm-8 panel-title">
                    Edit user
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>

            <div class="panel-body">
                {!! Form::open(['route' => ['users.update',$user], 'class' => 'form-horizontal panel']) !!}
                <div class="row">

                    <div style="padding-left: 25px;" class="form-group col-md-3 {!! $errors->has('name') ? 'has-error' : '' !!}">
                        Nom
                        {!! Form::text('name', $user->name , ['class' => 'form-control']) !!}
                    </div>
                    <div style="padding-left: 25px;padding-top: 0px;" class="form-group col-md-3 {!! $errors->has('email') ? 'has-error' : '' !!}">
                        Email
                        {!! Form::email('email', $user->email, ['class' => 'form-control' ]) !!}
                    </div>
                    <div style="padding-left: 25px;" class="form-group  col-md-3 {!! $errors->has('password') ? 'has-error' : '' !!}">
                        Password
                        {!! Form::password('password',['class' => 'form-control' ]) !!}
                    </div>
                    <div style="padding-left: 25px;" class="form-group col-md-3">
                        Confirmation
                        {!! Form::password('password_confirmation',['class' => 'form-control' ]) !!}
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-5"></div>
                    <div style="padding-left: 10px;" class="form-group col-md-2 ">
                        {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right form-control']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>



            </div>

        </div>

    </div>
@endsection

