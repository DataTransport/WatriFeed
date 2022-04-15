<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Collaborative web solution for GTFS multiple workflow management."/>
    <meta name="author" content="Data Transport"/>

    <link rel="icon" href="/images/WatriFeed_logo.png">

    <title>Watrifeed | Dashboard</title>

    @include('layouts.partials._styleHead')
    @yield('add_head')

    <style>
        .dataTables_wrapper > table.dataTable thead td,
        .dataTables_wrapper > table.dataTable tbody td,
        .dataTables_wrapper > table.dataTable tfoot td,
        .dataTables_wrapper > table.dataTable thead th,
        .dataTables_wrapper > table.dataTable tbody th,
        .dataTables_wrapper > table.dataTable tfoot th {
            border: 1px solid #003caf !important;
            padding-bottom: 0px !important;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 0px 10px !important;
        }

        .table-bordered > thead > tr > th, .table-bordered > thead > tr > td {
            color: #000000;
            border-bottom: 0 !important;
        }

        .control-label {
            color: #003caf;
        }

        .form-group > input {
            border: 1px solid #003caf;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #003caf;
            border-radius: 4px;
        }

        .watri_hr {
            border-top: 1px solid #3894ff;
            margin-top: 0;
            margin-bottom: 0;
        }

        .title {
            font-weight: bold;
        }

        .sous_title {
            font-weight: bold;
            font-size: 26px;
            color: #003caf;
        }

        .sous_sous_title {
            font-weight: bold;
            font-size: 20px;
            color: #000000;
        }

        .page-container .sidebar-menu {
            display: table-cell;
            vertical-align: top;
            background: #303641;
            width: 180px;
            position: relative;
            z-index: 100;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .parameter {
            background-color: #b1b9c0;
            padding: 2px 5px;
            text-align: center;
            width: fit-content;
            border-radius: 5px;
        }

        .code {
            background-color: #f8f8f8;
            padding: 5px;
        }

        .request_background {
            color: white;
            background-color: black;
            font-weight: bold;
            font-size: 14px;
        }

        .comment {
            color: #6dd0fd;
            font-weight: bold;
            font-size: 14px;
        }

        .value {
            color: #0d7eff;
        }

        .syntax {
            color: #000000;
            font-weight: bold;
            border: 2px solid #2196F3;
        }

        .syntax_example {
            color: #8BC34A;
        }

        .syntax_tr {
            border: 2px solid #2195f3;
        }

        .red {
            color: red;
            font-weight: bold;
        }

        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #2195f3;
        }

        .param {
            font-weight: bold;

        }

    </style>
</head>
<body class="page-body  skin-blue">

<div class="page-container @yield('sidebar','')">
    <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    {{--menu--}}
    <div class="sidebar-menu" style="">

        <div class="sidebar-menu-inner">

            <header class="logo-env" style="padding: 20px 5px;padding-bottom: 0;">

                <!-- logo -->
                <div class="logo">
                    <a href="{{ url('/') }}" style="width: 135px;position: relative;top: -23px;text-align: center">
                        <h3 style="color: #fff; font-size: 16px;margin-bottom: 0px;font-family: unset;text-align: center;">
                            Watri<span style="color: #31bfff;">Feed-API</span></h3>
                        <hr style="margin-top: 0px; margin-bottom: 4px;
                        border: 0;border-top: 1px solid #ff0000;
                        width: 110px;
">
                        <span style="font-size: 10px; color: #fff; font-weight: bold;"><span
                                style="color:#31bfff ">API</span>  | Documentation</span>
                    </a>
                </div>

                <!-- logo collapse icon -->
                <div class="sidebar-collapse">
                    <a href="#" class="sidebar-collapse-icon">
                        <!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>


                <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                <div class="sidebar-mobile-menu visible-xs">
                    <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

            </header>

            <hr style="margin-top: 0; margin-bottom: 0;">

            <ul id="main-menu" class="main-menu">
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                <li class="has-sub">
                    <a href="index.html">
                        <i class="entypo-cog"></i>
                        <span class="title">General options</span>
                    </a>
                    <ul>
                        <li>
                            <a href="#requests">
                                <span class="title">Requests</span>
                            </a>
                        </li>
                        <li>
                            <a href="#responses">
                                <span class="title">Responses</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>

    </div>


    <div class="main-content">

        @include('flash-message')


        <hr class="watri_hr">
        <div class="row" style="color: black">
            <div class="col-md-12">
                <h3 class="title">Watri<span style="color: #025ac1; ">Feed-API</span> Presentation</h3>
                <hr>
                <div style="font-size: 17px;font-family: 'Poppins', sans-serif;text-align: justify">
                    <p>WatriFeed-API is an open source Web API, designed and developed by the Data-Transport project
                        team.</p>
                    <p>The main goal is to provide daily information to developers, to enable them to implement
                        solutions based on the transport network.</p>
                    <p>WatriFeed-API offers a programming interface (API) that allows developers to make the most of the
                        data on the urban transport network of African cities, without having a technical knowledge of
                        the GTFS file in which the data is available.
                        <br>
                        It allows access to the following services :</p>
                    <ul>
                        <li>Transport lines</li>
                        <li>Trips on a line</li>
                        <li>Stops on each trip</li>
                        <li>The travel time of each trip</li>
                        <li>All the stops</li>
                        <li>The frequency of each trip</li>
                        <li>The calculation of trips to a destination.</li>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="watri_hr">
        <h1 class="sous_title" id="requests">Requests</h1>
        <hr class="watri_hr">
        <div class="row" style="color: black">
            <div class="col-md-6">
                <br>
                <div style="font-size: 17px;font-family: 'Poppins', sans-serif;text-align: justify">
                    <p>
                        All WatriFeed-API requests use a common structure. <br>
                        The following syntax applies to all services, unless otherwise specified.
                    </p>

                    <div>
                        <table class="table table table-striped table-dark table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">Parameter</th>
                                <th scope="col" colspan="2">Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td scope="row">
                                    <div class="parameter">gtfs_id</div>
                                </td>
                                <td colspan="2">
                                    Refers to the identifier of the GTFS requested about countries data
                                    <br> example: Mali/Bamako id = 12
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">
                                    <div class="parameter">service_id</div>
                                </td>
                                <td colspan="2">Refers to the identifier of the service requested.</td>
                            </tr>
                            <tr>
                                <td scope="row">
                                    <div class="parameter">service</div>
                                </td>
                                <td colspan="2">Can take one of the following values:
                                    <span class="value">
                                        route, routes, trip, trips, stop,
                                        stops, destinations, shape, shapes
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">
                                    <div class="parameter">api_key</div>
                                </td>
                                <td colspan="2">
                                    Designates the api-key of the user account on the watrifeed platform.
                                    You must <a target="_blank" href="{{url('/register')}}">
                                        register<img src="/images/blank_link.svg" alt="">
                                    </a> to get it

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <p>The passage of <span class="parameter">option=value</span> is optional, it enriches the
                            response of the query.
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                <div class="code" style="height: auto">
                    <br><br><br><br>
                    <table class="table"  style="width: 100%">
                        <thead>
                        <tr class="syntax_tr">
                            <th scope="col" class="syntax">
                                GET
                            </th>
                            <th scope="col" class="syntax" style="font-size: 12px">
                                <span>/api/{<span class="red">gtfs_id</span>}/[{<span class="red">service_id</span>}/]
                                    {<span class="red">service</span>}?<span class="red">api_key</span>=value[&option=value[ &option=value][&…]…]</span>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <br><br>
                    <h3 class="title">Example requests</h3>
                    <table class="table" style="width: 100%">
                        <thead>
                        <tr>
                            <th scope="col" class="request_background">
                                GET <br><br>
                            </th>
                            <th scope="col" class="request_background">
                                <span class="comment"># Request on line Sotrama.L1 [Bamako]</span>
                                <button style="float: right;padding: 0 5px;" onclick="copy_function('general')"
                                        class="btn btn-default">copy
                                </button>
                                <br>
                                <span>
                                    curl
                                    <span class="syntax_example" id="general">'https://watrifeed.ml/api/12/Sotrama.L1/route?api_key=5148f6ae2d6490aac1be1dc6cb461f7c&trips=true'</span>

                                </span>

                            </th>
                        </tr>
                        </thead>
                    </table>
                    <br><br><br><br><br><br><br><br><br>
                </div>
            </div>
        </div>
        <hr class="watri_hr">
        <h1 class="sous_title" id="responses">Responses</h1>
        <hr class="watri_hr">

        <div class="row" style="color: black">

            <div class="col-md-6">
                <br>
                <div style="font-size: 17px;font-family: 'Poppins', sans-serif;text-align: justify">
                    <p>
                        Each API response is a json object with properties that contain a number, string, object or
                        array.
                    </p>

                </div>


            </div>
            <div class="col-md-6">
                <div class="code" style="height: auto">
                    <h3 class="title">Example responses</h3>
                    <div>
                        <img src="/images/response_example.png" alt=""
                             style="position: relative;margin: auto;display: block;">
                    </div>
                </div>
            </div>
        </div>
        <hr class="watri_hr">
        <h1 class="sous_title">Services</h1>
        <hr class="watri_hr">
        <div class="row" style="color: black">

            <div class="col-md-6">
                <h2 class="sous_sous_title">Routes service</h2>
                <div style="font-size: 17px;font-family: 'Poppins', sans-serif;text-align: justify">
                    <p> This service provides access to the lines of the transport network </p>
                    <hr class="watri_hr">
                    <strong> - Get all transport network lines </strong>
                    <hr class="watri_hr">
                    <br>
                    <br>

                    <table class="table table-striped table-dark table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Parameter</th>
                            <th scope="col" colspan="2">Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="row">
                                <div class="param">route_id</div>
                            </td>
                            <td colspan="2">Route ID</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">agency_id</div>
                            </td>
                            <td colspan="2">Network Agency Identifier</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_short_name</div>
                            </td>
                            <td colspan="2">Short version of the line name</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_long_name</div>
                            </td>
                            <td colspan="2">The full name of the line</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_desc</div>
                            </td>
                            <td colspan="2">Description of the line</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_type</div>
                            </td>
                            <td colspan="2">Describes the mode of transport used for the line.</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_url</div>
                            </td>
                            <td colspan="2">Web address of the line</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_color</div>
                            </td>
                            <td colspan="2">Colour of the line corresponding to that used in media intended for the
                                public. Ex : FFFFFF
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_text_color</div>
                            </td>
                            <td colspan="2">Readable color for the text to be displayed on the background color
                                route_color
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">route_sort_order</div>
                            </td>
                            <td colspan="2">Specifies the order in which the lines will be presented to users.</td>
                        </tr>
                        </tbody>
                    </table>


                </div>

            </div>
            <div class="col-md-6">
                <div class="code" style="height: auto">
                    <br><br><br><br><br>
                    <table class="table">
                        <thead>
                        <tr class="syntax_tr">
                            <th scope="col" class="syntax">
                                GET
                            </th>
                            <th scope="col" class="syntax" style="font-size: 14px">
                                <span>/api/{<span class="red">gtfs_id</span>}/
                                    <span class="red">routes</span>?<span class="red">api_key</span>=value</span>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <br><br>
                    <h3 class="sous_sous_title">Example requests</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col" class="request_background">
                                GET <br><br>
                            </th>
                            <th scope="col" class="request_background">
                                <span class="comment"># Request on the lines [Bamako]</span>
                                <button style="float: right;padding: 0 5px;" onclick="copy_function('routes')"
                                        class="btn btn-default">copy
                                </button>
                                <br>
                                <span>
                                    curl
                                    <span class="syntax_example" id="routes">'https://watrifeed.ml/api/12/routes?api_key=5148f6ae2d6490aac1be1dc6cb461f7c'</span>
                                </span>
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <h3 class="sous_sous_title">Example response</h3>
                    <div>
                        <img style="display: block; margin: auto" src="/images/routes_request_response.png"
                             alt="routes_request_response" width="70%">
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="color: black">

            <div class="col-md-6">
                <div style="font-size: 17px;font-family: 'Poppins', sans-serif;text-align: justify">
                    <br>
                    <hr class="watri_hr">
                    <strong> - Get a single line </strong>
                    <hr class="watri_hr">
                    <p>
                        In additional to the general options, the following options can be supported for this service:
                    </p>
                    <table class="table table-striped table-dark table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Option</th>
                            <th scope="col">Values</th>
                            <th scope="col" colspan="2">Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="row">
                                <div class="param">trips</div>
                            </td>
                            <td>true, false</td>
                            <td>Get a route with his trips</td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <div class="param">stops</div>
                            </td>
                            <td>true, false</td>
                            <td>Get a route with his stops</td>
                        </tr>


                        </tbody>
                    </table>

                </div>

            </div>
            <div class="col-md-6">
                <div class="code" style="height: auto">
                    <br><br>
                    <table class="table">
                        <thead>
                        <tr class="syntax_tr">
                            <th scope="col" class="syntax">
                                GET
                            </th>
                            <th scope="col" class="syntax" style="font-size: 14px">
                                <span>/api/{<span class="red">gtfs_id</span>}/
                                    {<span class="red">route_id</span>}/
                                    <span class="red">route</span>?
                                    <span class="red">api_key</span>=value

                                [[&<span style="color: green">trips</span>={true | false}]
                                    [&<span style="color: green">stops</span>={true | false}]
                                </span>
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <br><br>
                    <h3 class="sous_sous_title">Example requests</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col" class="request_background">
                                GET <br><br>
                            </th>
                            <th scope="col" class="request_background">
                                <span class="comment"># Request on line Sotrama.L1 [Bamako]</span>
                                <button style="float: right; padding: 0 5px;" onclick="copy_function('route')"
                                        class="btn btn-default">copy
                                </button>
                                <br>
                                <span>
                                    curl
                                    <span class="syntax_example" id="route">'https://watrifeed.ml/api/12/Sotrama.L1/route?api_key=5148f6ae2d6490aac1be1dc6cb461f7c&trips=true&stops=true'</span>
                                </span>
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <h3 class="sous_sous_title">Example response</h3>
                    <div>
                                                <img style="display: block; margin: auto" src="/images/route_request_response.png"
                                                     alt="routes_request_response" width="70%">
                    </div>
                </div>
            </div>
        </div>


        @include('layouts.partials._footer')
    </div>
</div>

@include('layouts.partials._scriptFooter')
@yield('add_footer')
<script !src="">

    function copy_function(id) {
        /* Get the text field */
        // const copyText = document.getElementById(id);

        // console.dir(copyText);

        /* Select the text field */
        // copyText.select();
        // copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        // document.execCommand("copy");

        /* Alert the copied text */
        // alert("Copied the text: " + copyText.value);
        const copyText = document.getElementById(id);
        const textArea = document.createElement("textarea");
        textArea.value = "curl " + copyText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
    }
</script>
</body>
</html>
