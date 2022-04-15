<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Collaborative web solution for GTFS multiple workflow management." />
    <meta name="author" content="Data Transport" />

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
        .dataTables_wrapper > table.dataTable tfoot th{
            /*border: 1px solid #003caf !important;*/
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
        .control-label{
            color: #003caf;
        }
        .form-group > input{
            border: 1px solid #003caf;
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #003caf;
            border-radius: 4px;
        }

        .watri_hr {
            border-top: 1px solid #3894ff;
            margin-top: 0px;
            margin-bottom: 7px;
        }
    </style>
</head>
<body class="page-body  skin-blue" style="font-size: 15px;">

<div class="page-container @yield('sidebar','')"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

   @include('layouts.partials._menu')

    <div class="main-content">

        @include('layouts.partials._header')
        <hr class="watri_hr">
        @include('flash-message')


        <div class="row">
            @yield('content')
        </div>


        @include('layouts.partials._footer')
    </div>


</div>

@include('layouts.partials._scriptFooter')
@yield('add_footer')
</body>
</html>
