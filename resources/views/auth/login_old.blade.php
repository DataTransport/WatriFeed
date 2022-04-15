@extends('layouts.app')
@section('style_login_header')

    {!! app('html')->style('neon/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') !!}
    {!! app('html')->style('neon/css/font-icons/entypo/css/entypo.css') !!}
    {!! app('html')->style('neon/css/bootstrap.css') !!}
    {!! app('html')->style('neon/css/neon-core.css') !!}
    {!! app('html')->style('neon/css/neon-theme.css') !!}
    {!! app('html')->style('neon/css/neon-forms.css') !!}
    {!! app('html')->style('neon/css/custom.css') !!}

@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="login-container">

        <div class="login-header login-caret">

            <div class="login-content">

                <a href="index.html" class="logo">
                    <img src="assets/images/logo@2x.png" width="120" alt="" />
                </a>

                <p class="description">Dear user, log in to access the admin area!</p>

                <!-- progress bar indicator -->
                <div class="login-progressbar-indicator">
                    <h3>43%</h3>
                    <span>logging in...</span>
                </div>
            </div>

        </div>

        <div class="login-progressbar">
            <div></div>
        </div>

        <div class="login-form">

            <div class="login-content">

                <div class="form-login-error">
                    <h3>Invalid login</h3>
                    <p>Enter <strong>demo</strong>/<strong>demo</strong> as login and password.</p>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="entypo-user"></i>
                            </div>

                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('E-Mail Address') }}" autocomplete="off" />
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>
                    {{--                <div class="form-group row">--}}
                    {{--                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

                    {{--                    <div class="col-md-6">--}}
                    {{--                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>--}}

                    {{--                        @if ($errors->has('email'))--}}
                    {{--                            <span class="invalid-feedback" role="alert">--}}
                    {{--                                        <strong>{{ $errors->first('email') }}</strong>--}}
                    {{--                                    </span>--}}
                    {{--                        @endif--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}

                    <div class="form-group">

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="entypo-key"></i>
                            </div>

                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password" autocomplete="off" />

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>

                    {{--                <div class="form-group row">--}}
                    {{--                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

                    {{--                    <div class="col-md-6">--}}
                    {{--                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>--}}

                    {{--                        @if ($errors->has('password'))--}}
                    {{--                            <span class="invalid-feedback" role="alert">--}}
                    {{--                                        <strong>{{ $errors->first('password') }}</strong>--}}
                    {{--                                    </span>--}}
                    {{--                        @endif--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-login">
                            <i class="entypo-login"></i>
                            Login In
                        </button>
                    </div>
                    {{--                <div class="form-group row mb-0">--}}
                    {{--                    <div class="col-md-8 offset-md-4">--}}
                    {{--                        <button type="submit" class="btn btn-primary">--}}
                    {{--                            {{ __('Login') }}--}}
                    {{--                        </button>--}}

                    {{--                        @if (Route::has('password.request'))--}}
                    {{--                            <a class="btn btn-link" href="{{ route('password.request') }}">--}}
                    {{--                                {{ __('Forgot Your Password?') }}--}}
                    {{--                            </a>--}}
                    {{--                        @endif--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                    <div class="login-bottom-links">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link">Forgot your password?</a>
                        @endif
                        <br />

                        <a href="#">ToS</a>  - <a href="#">Privacy Policy</a>

                    </div>
                </form>

            </div>

        </div>

    </div>
</div>
@endsection

@section('script_login_footer')
    <!-- Bottom scripts (common) -->
    {!! app('html')->script('neon/js/gsap/TweenMax.min.js') !!}
    {!! app('html')->script('neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') !!}
    {!! app('html')->script('neon/js/bootstrap.js') !!}
    {!! app('html')->script('neon/js/joinable.js') !!}
    {!! app('html')->script('neon/js/resizeable.js') !!}
    {!! app('html')->script('neon/js/neon-api.js') !!}
    {!! app('html')->script('neon/js/jquery.validate.min.js') !!}
    {!! app('html')->script('neon/js/neon-login.js') !!}


    <!-- JavaScripts initializations and stuff -->

    {!! app('html')->script('neon/js/neon-custom.js') !!}



    <!-- Demo Settings -->
    {!! app('html')->script('neon/js/neon-demo.js') !!}

@stop
