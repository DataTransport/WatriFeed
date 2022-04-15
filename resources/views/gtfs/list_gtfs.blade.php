@extends('layouts.master')
@php
    $list_gtfs = 'active';
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
        footer {
            position: relative !important;
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
                    GTFS List
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
                        <th>Gtfs</th>
                        <th>Creation Date</th>
                        <th>Update Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=1;
                    @endphp
                    @if((int)\Illuminate\Support\Facades\Auth::id()===1)
                        @foreach (App\Gtfs::all() as $gtfs)
                            @php
                                $user = \App\User::find($gtfs->user_id)
                            @endphp
                            <tr class="odd gradeX">
                                <td>{{$i++}}</td>
                                <td> {{$gtfs->name}} | <strong style="color: #007a00">{{$user->name}}</strong></td>
                                <td class="center">{{$gtfs->created_at}}</td>
                                <td class="center">{{$gtfs->updated_at}}</td>
                                <td>
                                    <a data-name="{{$gtfs->name}}"
                                       class="edit_gtfs btn btn-info btn-sm btn-icon icon-left" href="javascript:"
                                       onclick="jQuery('#modal-6').modal('show', {backdrop: 'static'});">
                                        <i class="entypo-pencil"></i>
                                        Edit
                                    </a>

                                    <button
                                        class="delete_gtfs btn btn-danger btn-sm btn-icon icon-left" id="{{$gtfs->id}}"
                                        data-named="{{$gtfs->name}}">
                                        <i class="entypo-cancel"></i>
                                        Delete
                                    </button>


                                    <a href="{{ url("/export/$gtfs->name") }}"
                                       class="btn btn-success btn-sm btn-icon icon-left">
                                        <i class="entypo-upload"></i>
                                        Export
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @foreach (App\Gtfs::where('user_id',\Illuminate\Support\Facades\Auth::id())->get() as $gtfs)
                            <tr class="odd gradeX">
                                <td>{{$i++}}</td>
                                <td> {{$gtfs->name}}</td>
                                <td class="center">{{$gtfs->created_at}}</td>
                                <td class="center">{{$gtfs->updated_at}}</td>
                                <td>
                                    <a data-name="{{$gtfs->name}}"
                                       class="edit_gtfs btn btn-info btn-sm btn-icon icon-left" href="javascript:"
                                       onclick="jQuery('#modal-6').modal('show', {backdrop: 'static'});">
                                        <i class="entypo-pencil"></i>
                                        Edit
                                    </a>

                                    <button
                                        class="delete_gtfs btn btn-danger btn-sm btn-icon icon-left" id="{{$gtfs->id}}"
                                        data-named="{{$gtfs->name}}">
                                        <i class="entypo-cancel"></i>
                                        Delete
                                    </button>


                                    <a href="{{ url("/export/$gtfs->name") }}"
                                       class="btn btn-success btn-sm btn-icon icon-left">
                                        <i class="entypo-upload"></i>
                                        Export
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif


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
    <script>
        $(document).on('click', '.btn_s', function () {
            $(this).hide();
            $('#loading').show();
            // $(this).prop('value','loading...');
        });

        function messageFlash(message, type = "success") {
            let opts = {
                "closeButton": true,
                "debug": false,
                "positionClass": rtl() || public_vars.$pageContainer.hasClass('right-sidebar') ? "toast-top-left" : "toast-top-right",
                "toastClass": "red",
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            if (type === 'success') {
                toastr.success(message, opts);
            } else if (type === 'info') {
                toastr.info(message, opts);
            } else {
                toastr.error(message, opts);
            }


        }

        function form(data_delete = '') {
            if (c !== 'ok') {
                $('#form').on('submit', function (e) {
                    e.preventDefault();

                    c = 'ok';

                    let data = $(this).serialize();
                    let url = $(this).attr('action');
                    // let url = url;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: 'POST',
                        url: url,
                        data: data,
                        success: function (data) {

                            data = $.parseJSON(data);
                            console.dir(data);

                            if (data.response === 'ok') {

                                // $('.btn_s').val('Wait...');
                                messageFlash('Password correct');
                                setTimeout(function () {
                                    // document.location.href = '/gtfs/'+data.id+'/edit';
                                    if (script === 'edit') {
                                        post('gtfs/edit_', {
                                            pass: data.pass,
                                            id: data.id,
                                            "_token": "{{ csrf_token() }}"
                                        });
                                    }
                                    if (script === 'delete') {
                                        if (confirm(`Are you sure you want to delete ${data_delete.name} GTFS?`)) {
                                            $.ajaxSetup({
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                }
                                            });
                                            $.ajax({
                                                url: "/gtfs/" + data_delete.id,
                                                method: "DELETE",
                                                data: {id: data_delete.id},
                                                success: function (data) {
                                                    messageFlash(data, 'success');

                                                    setInterval(function () {
                                                        document.location.href = '/gtfs/';

                                                    }, 2000);
                                                }
                                            });

                                        }
                                    }


                                }, 1000);

                            } else {
                                messageFlash('Password incorrect', 'error');
                                let btn = $('.btn_s');
                                btn.show();
                                $('#loading').hide();
                                // btn.removeClass('disabled');
                                // btn.val('Submit');
                            }


                        },
                        error: function (data) {
                            const errors = $.parseJSON(data.responseText);
                            let message = '';
                            console.log(errors.errors.password[0]);
                            messageFlash(errors.errors.password[0], 'error');
                            $('.div_pass').addClass('has-error');
                            // alertify.error(message);

                            let btn = $('.btn_s');
                            btn.show();
                            $('#loading').hide();

                            return false;

                            // messageFlash(message,'error');
                        }
                    })
                })
            }

        }

        $('.delete_gtfs').on('click', function () {

            // console.dir($('#form').on('submit'));
            const id = $(this).attr("id");
            const name = $(this).data('named');
            $('input[name=name]').val(name);

            script = 'delete';
            form({id: id, name: name});
            jQuery('#modal-6').modal('show', {backdrop: 'static'});

        });

        $(document).on('click', '.edit_gtfs', function () {
            $('input[name=name]').val($(this).data('name'));
            script = 'edit';
            form();
        })
    </script>


    {!! app('html')->script('neon/js/toastr.js') !!}

    <script>
        function post(path, params, method = 'post') {

            // The rest of this code assumes you are not using a library.
            // It can be made less wordy if you use one.
            const form = document.createElement('form');
            form.method = method;
            form.action = path;

            for (const key in params) {
                if (params.hasOwnProperty(key)) {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = key;
                    hiddenField.value = params[key];

                    form.appendChild(hiddenField);
                }
            }


            document.body.appendChild(form);
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    }
                });
            });
            form.submit();
        }

        $(document).ready(function () {

            c = '';
            script = '';

        })
    </script>


@stop
