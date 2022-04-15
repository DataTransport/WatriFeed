<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Collaborative web solution for GTFS multiple workflow management." />
    <meta name="author" content="Data Transport" />

    <link rel="icon" href="/images/WatriFeed_logo.png">

    <title>Watrifeed | Login</title>

    {!! app('html')->style('neon/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') !!}
    {!! app('html')->style('neon/css/font-icons/entypo/css/entypo.css') !!}
    {!! app('html')->style('neon/css/bootstrap.css') !!}
    {!! app('html')->style('neon/css/neon-core.css') !!}
    {!! app('html')->style('neon/css/neon-theme.css') !!}
    {!! app('html')->style('neon/css/neon-forms.css') !!}
    {!! app('html')->style('neon/css/custom.css') !!}

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">




    {!! app('html')->script('neon/js/jquery-1.11.3.min.js') !!}

    <!--[if lt IE 9]>{!! app('html')->script('neon/js/ie8-responsive-file-warning.js') !!}<![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .is-invalid{
            border: solid 1px red !important;
        }
        .login-page .login-header {
            /*background: #373e4a;*/

            background-color: white;
            -webkit-animation-name: animation; /* Safari 4.0 - 8.0 */
            -webkit-animation-duration: 4s; /* Safari 4.0 - 8.0 */
            animation-name: animation;
            animation-duration: 4s;

        }
        /*.login-caret:after{*/
        /*    border-color: #fff;*/
        /*    -webkit-animation-name: animation2; !* Safari 4.0 - 8.0 *!*/
        /*    -webkit-animation-duration: 4s; !* Safari 4.0 - 8.0 *!*/
        /*    animation-name: animation2;*/
        /*    animation-duration: 4s;*/
        /*}*/

        /* Safari 4.0 - 8.0 */
        @-webkit-keyframes animation {
            from {background-color: #373e4a;}
            to {background-color: white;}
        }

        /* Standard syntax */
        @keyframes animation {
            from {background-color: #373e4a;}
            to {background-color: white;}
        }

        /* Safari 4.0 - 8.0 */
        @-webkit-keyframes animation2 {
            from {border-color: #373e4a transparent transparent transparent;}
            to { border-color: #ffffff transparent transparent transparent;}
        }

        /* Standard syntax */
        @keyframes animation2 {
            from {border-color: #373e4a transparent transparent transparent;}
            to { border-color: #ffffff transparent transparent transparent;}
        }

        .login-page .login-header.login-caret:after {
            position: absolute;
            content: '';
            left: 50%;
            bottom: 0;
            margin-left: -12.5px;
            width: 0px;
            height: 0px;
            border-style: solid;
            border-width: 13px 12.5px 0 12.5px;
            border-color: #ffffff transparent transparent transparent;
            bottom: -13px;
            -moz-transition: all 550ms ease-in-out;
            -webkit-transition: all 550ms ease-in-out;
            -o-transition: all 550ms ease-in-out;
            transition: all 550ms ease-in-out;
            -webkit-animation-name: animation2; /* Safari 4.0 - 8.0 */
                -webkit-animation-duration: 4s; /* Safari 4.0 - 8.0 */
                animation-name: animation2;
                animation-duration: 4s;
        }
    </style>

</head>
<body class="page-body login-page login-form-fall" data-url="">


<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
    var baseurl = '';
</script>

<div class="login-container">

    <div class="login-header login-caret" style="padding: 0px">

        <div class="login-content" style="width: 238px">


{{--            <p class="description">Dear user, log in to access the admin area!</p>--}}
            <a href="#" >
                <img src="" width="120" />
                <h3 style="font-size: 51px;margin-bottom: 0px;font-family: unset;text-align: center;">Watri<span style="color: #025ac1;">Feed</span></h3>
                <hr style="margin-top: 0px; margin-bottom: 4px;border: 0;border-top: 1px solid #ff0000;">
                <span style="font-size: 13px; color: #025ac1; font-weight: bold;">GTFS Multiple Workflow Management.</span>
            </a>

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

                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('E-Mail Address') }}"  />

                    </div>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                                        <span style="color: red;">{{ $errors->first('email') }}</span>
                                    </span>
                    @endif
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
                                        <span style="color: #f00;">{{ $errors->first('password') }}</span>
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
                    <button style="text-align: center" type="submit" class="btn btn-primary btn-block btn-login">
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
{{--                <div class="login-bottom-links">--}}
{{--                    @if (Route::has('password.request'))--}}
{{--                        <a href="{{ route('password.request') }}" class="link">Forgot your password?</a>--}}
{{--                    @endif--}}
{{--                    <br />--}}

{{--                    <a href="#">ToS</a>  - <a href="#">Privacy Policy</a>--}}

{{--                </div>--}}
            </form>

        </div>

    </div>

</div>


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

</body>
</html>
