<?php
    $list_gtfs = 'active';
    $edit_ ='edit_';
    $ajax_select=true;
?>

<?php $__env->startSection('sidebar','sidebar-collapsed'); ?>

<?php $__env->startSection('add_head'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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

        .btn-block {
            font-size: large;
            font-weight: bold;
        }

        .select2-dropdown {
            z-index: 20001 !important;
        }

        .border_red {
            border: 1px red solid !important;
        }

        .select2-selection__placeholder {
            color: #000 !important;
        }

        body {
            color: black;
        }
        .bg-success{
            box-shadow: 0px 0px 9px black !important;
            background-color: #bdedbc !important;
        }

    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
          integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet-src.js"
            integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA=="
            crossorigin=""></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/leaflet.markercluster/example/screen.css"/>

    <link rel="stylesheet" href="/leaflet.markercluster/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="/leaflet.markercluster/dist/MarkerCluster.Default.css"/>
    <script src="/leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
    <script src="/leaflet.markercluster/example/realworld.10000.js"></script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <a href="<?php echo e(route('gtfs.edit', ['gtf' =>$gtfs->id ])); ?>" class="btn btn-primary">Back</a>
    <hr>

    <div class="col-md-12">
        <?php if(count($errors) > 0): ?>



            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


        <span id="gtfs" hidden><?php echo e($gtfs->id); ?></span>

       <div class="row">
            <div style="border: 1px solid #000000;" class="panel panel-primary" data-collapsed="0">
                <div class="col-sm-1" style="padding-top: 10px;font-size: 18px;">
                    <i style="color: #fff;font-size: xx-large;" class="fa fa-map-marker"></i>
                </div>
                <div style="border-bottom: 1px solid #fff;background: #000000;color: white;" class="panel-heading">

                    <div style="font-weight: bold;font-size: 18px; text-align: center"
                         class="col-sm-8 panel-title">
                        <div class="row">
                            <div class="col-sm-4">
                                Stops
                            </div>
                            <div class="col-sm-4" >
                                <form action="/stop" method="get">
                                    <input type="text" hidden value="<?php echo e(csrf_token()); ?>" name="t">
                                    <input type="text" hidden value="<?php echo e($gtfs->id); ?>" name="g">
                                    <div class="input-group" style="width: 200px;">
                                        <input type="text" class="form-control" name="search" placeholder="StopID">
                                        <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><i style="color:#fff;"
                                                                                 class="entypo-search"></i> </button>
                            </span>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4">
                                <form action="/stop" method="get">
                                    <input type="text" hidden value="<?php echo e(csrf_token()); ?>" name="t">
                                    <input type="text" hidden value="<?php echo e($gtfs->id); ?>" name="g">
                                    <div class="input-group" style="width: 200px;">
                                        <input type="text" class="form-control" name="search_name" placeholder="StopName">
                                        <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><i style="color:#fff;"
                                                                                 class="entypo-search"></i> </button>
                            </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>




                    <div class="panel-options">
                        <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">
                            Records <?php echo e($stops->firstItem()); ?> - <?php echo e($stops->lastItem()); ?> of <?php echo e($stops->total()); ?> (for page <?php echo e($stops->currentPage()); ?> )
                        </span>
                        <a href="#" data-rel="collapse">
                            <i style="color: #fff;" class="entypo-down-open"></i>
                        </a>
                        <a href="javascript:" onclick="$('#modal-6').modal('show', {backdrop: 'static'});"
                           id="add_">
                            <i style="color: #fff;" class="entypo-plus-circled"></i>
                        </a>

                    </div>
                </div>
                <div class="panel-body">


                    <table class="table table-bordered datatable" id="stops_data" data-order='[[ 1, "asc" ]]'
                           data-page-length='10'>
                        <thead>
                        <tr>
                            <th title="Stop id">ID</th>
                            <th title="Stop code">Code</th>
                            <th title="Stop name">Name</th>
                            <th title="Description">Desc</th>
                            <th title="Stop Latitude">Lat</th>
                            <th title="Stop Longitude">Lon</th>
                            <th title="Zone ID">Z-ID</th>
                            <th title="Url">Url</th>
                            <th title="Location Type">L-Type</th>
                            <th title="Parent Station">P-Station</th>
                            <th title="Timezone">Tzone</th>
                            <th title="Wheelchair Boarding">WB</th>
                            <th title="Level ID">L-ID</th>
                            <th title="Platform Code">P-Code</th>
                            <th title="Action">A</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $stops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="odd gradeX" id="<?php echo e($stop->stop_id); ?>" onclick='markerFunction($(this)[0].id); test()'>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_id"><?php echo e($stop->stop_id); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_code"><?php echo e($stop->stop_code); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_name"><?php echo e($stop->stop_name); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_desc"><?php echo e($stop->stop_desc); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_lat"><?php echo e($stop->stop_lat); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_lon"><?php echo e($stop->stop_lon); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="zone_id"><?php echo e($stop->zone_id); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_url"><?php echo e($stop->stop_url); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="location_type"><?php echo e($stop->location_type); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="parent_station"><?php echo e($stop->parent_station); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="stop_timezone"><?php echo e($stop->stop_timezone); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="wheelchair_boarding"><?php echo e($stop->wheelchair_boarding); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="level_id"><?php echo e($stop->level_id); ?></td>
                                <td class="update <?php echo e($stop->id); ?>" data-id="<?php echo e($stop->id); ?>"
                                    data-column="platform_code"><?php echo e($stop->platform_code); ?></td>

                                <td>
                                    <button style="display: none;" type="button" name="save_btn"
                                            class="btn btn-success btn-sm save_btn<?php echo e($stop->id); ?> save_btn"
                                            data-rowid="<?php echo e($stop->id); ?>">save
                                    </button>
                                    <button type="button" name="edit_btn"
                                            class="btn btn-info btn-sm edit_btn<?php echo e($stop->id); ?> edit_btn_"
                                            data-rowid="<?php echo e($stop->id); ?>"
                                            data-stop_id="<?php echo e($stop->stop_id); ?>"
                                            data-stop_code="<?php echo e($stop->stop_code); ?>"
                                            data-stop_name="<?php echo e($stop->stop_name); ?>"
                                            data-stop_desc="<?php echo e($stop->stop_desc); ?>"
                                            data-stop_lat="<?php echo e($stop->stop_lat); ?>"
                                            data-stop_lon="<?php echo e($stop->stop_lon); ?>"
                                            data-zone_id="<?php echo e($stop->zone_id); ?>"
                                            data-stop_url="<?php echo e($stop->stop_url); ?>"
                                            data-location_type="<?php echo e($stop->location_type); ?>"
                                            data-parent_station="<?php echo e($stop->parent_station); ?>"
                                            data-stop_timezone="<?php echo e($stop->stop_timezone); ?>"
                                            data-wheelchair_boarding="<?php echo e($stop->wheelchair_boarding); ?>"
                                            data-level_id="<?php echo e($stop->level_id); ?>"
                                            data-platform_code="<?php echo e($stop->platform_code); ?>"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" name="delete" class="btn btn-danger btn-sm delete"
                                            id="<?php echo e($stop->id); ?>"><i class="fa fa-trash"></i></button>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>

                        <th title="Stop id">ID</th>
                        <th title="Stop code">Code</th>
                        <th title="Stop name">Name</th>
                        <th title="Description">Desc</th>
                        <th title="Stop Latitude">Lat</th>
                        <th title="Stop Longitude">Lon</th>
                        <th title="Zone ID">Z-ID</th>
                        <th title="Url">Url</th>
                        <th title="Location Type">L-Type</th>
                        <th title="Parent Station">P-Station</th>
                        <th title="Timezone">Tzone</th>
                        <th title="Wheelchair Boarding">WB</th>
                        <th title="Level ID">L-ID</th>
                        <th title="Platform Code">P-Code</th>
                        <th></th>
                        </tfoot>
                    </table>
                    <?php echo e($stops->links()); ?>




                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-gradient" data-collapsed="0">
                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title">Map Visualization</div>
                                    <div class="panel-options">
                                    </div>
                                </div>
                                <!-- panel body -->
                                <div class="panel-body">
                                    <div id="map" style="width:100%; height:500px;border: solid #1066b4; padding: 0px;"></div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal 6 (Long Modal)-->
    <div class="modal fade" id="modal-6">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="text-align:center;"></h4>
                </div>

                <form action="/stop/store" id="form">
                    <div class="modal-body">

                        <input hidden type="text" id="parent_station" name="parent_station">
                        <input hidden type="text" id="level_id" name="level_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stop-id" class="control-label">Stop ID <span
                                            class="field_required">*</span></label>
                                    <input type="text" class="form-control input" id="stop-id" name="stop_id">
                                    <strong class="errors_message stop_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="stop-name" class="control-label">Stop Name</label>
                                    <input type="text" class="form-control" id="stop-name" name="stop_name">
                                    <strong class="errors_message stop_name" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group" id="stop_lat">
                                    <label for="stop-lat" class="control-label">Stop Latitude</label>
                                    <input type="text" class="form-control" id="stop-lat" name="stop_lat">
                                    <strong class="errors_message stop_lat" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group" id="stop_lon">
                                    <label for="stop-lon" class="control-label">Stop Longitude</label>
                                    <input type="text" class="form-control" id="stop-lon" name="stop_lon">
                                    <strong class="errors_message stop_lon" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="zone-id" class="control-label">Zone ID</label>
                                    <input type="text" class="form-control" id="zone-id" name="zone_id">
                                    <strong class="errors_message zone_id" style="display: none;color: red;"></strong>
                                </div>
                                <div class="form-group">
                                    <label for="parent-station" class="control-label">Parent Station</label><br>
                                    <select class="parent_station_ form-control" style="width: 100%;"
                                            id="parent-station" name="parent_station"></select>
                                    <strong class="errors_message parent_station"
                                            style="display: none;color: red;"></strong>
                                </div>

                                <div class="form-group">
                                    <label for="level-id" class="control-label">Level ID</label><br>
                                    <select class="level_id_ form-control" style="width: 100%;" id="level-id"
                                            name="level_id"></select>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="stop-code" class="control-label">Stop Code</label>
                                    <input type="text" class="form-control" id="stop-code" name="stop_code">
                                </div>
                                <div class="form-group">
                                    <label for="stop-desc" class="control-label">Stop Description</label>
                                    <input type="text" class="form-control" id="stop-desc" name="stop_desc">
                                </div>
                                <div class="form-group">
                                    <label for="location-type" class="control-label">Location Type</label>
                                    <input type="text" class="form-control" id="location-type" name="location_type">
                                </div>
                                <div class="form-group">
                                    <label for="stop-lon" class="control-label">Stop Url</label>
                                    <input type="text" class="form-control" id="stop-lon" name="stop_url">
                                </div>
                                <div class="form-group">
                                    <label for="stop-timezone" class="control-label">Stop Timezone</label>
                                    <input type="text" class="form-control" id="stop-timezone" name="stop_timezone">
                                </div>
                                <div class="form-group">
                                    <label for="wheelchair-boarding" class="control-label">Wheelchair Boarding</label>
                                    <input type="text" class="form-control" id="wheelchair-boarding"
                                           name="wheelchair_boarding">
                                </div>
                                <div class="form-group">
                                    <label for="platform-code" class="control-label">Platform Code</label>
                                    <input type="text" class="form-control" id="platform-code" name="platform_code">
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="logo" id="w_l" style="position: relative;top: -128px;">

        <h3 style="color: #000000; font-size: 34px;margin-bottom: 0px;font-family: unset;text-align: center;">Watri<span
                style="color: #003caf;">Feed</span></h3>
        <hr style="margin-top: 0px; margin-bottom: 4px;border: 0;border-top: 1px solid #ff0000;">
        <span style="font-size: 20px; color: #000000; font-weight: bold;">Map Loading...</span>
    </div>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('styles_page'); ?>
    <?php echo app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css'); ?>

    <?php echo app('html')->style('neon/js/datatables/datatables.css'); ?>

    <?php echo app('html')->style('neon/js/select2/select2-bootstrap.css'); ?>

    <?php echo app('html')->style('neon/js/select2/select2.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts_page'); ?>
    <?php echo app('html')->script('neon/js/datatables/datatables.js'); ?>

    <?php echo app('html')->script('neon/js/neon-chat.js'); ?>


    <script src="/neon/js/bootstrap.js"></script>

    <?php echo app('html')->script('neon/js/toastr.js'); ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <?php echo app('html')->script('dataTable/js/functions.js'); ?>

    <?php echo app('html')->script('dataTable/js/stops.js'); ?>


    <script
        src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>


    <script type="text/javascript">

        $('.parent_station_').select2({
            ajax: {
                url: '/select2-stops-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.stop_id,
                                id: item.stop_id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('.level_id_').select2({
            ajax: {
                url: '/select2-levels-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.level_id,
                                id: item.level_id
                            }
                        })
                    };
                },
                cache: true
            }
        });


        $('#add_').on('click', function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Add Stop</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #00b000;">');
            $('#form').attr('action', '/stop/store');
            save_form();
            clear_form_modal();
        });
        $('.edit_btn_').on('click', function () {
            $('.modal-title').html('<span style="font-weight: bold;color: #084184;">Edit Stop</span>' +
                '<hr style="margin-top: -3px;margin-bottom: 0;border-top: 1px solid #2196F3;">');
            let id = $(this).data('rowid');
            $('#form').attr('action', '/stop/' + id);
            save_form('PUT');
            clear_form_modal();
            $('#modal-6').modal('show', {backdrop: 'static'});
            let stop_id = $(this).data('stop_id');
            let stop_code = $(this).data('stop_code');
            let stop_name = $(this).data('stop_name');
            let stop_desc = $(this).data('stop_desc');
            let stop_lat = $(this).data('stop_lat');
            let stop_lon = $(this).data('stop_lon');
            let zone_id = $(this).data('zone_id');
            let stop_url = $(this).data('stop_url');
            let location_type = $(this).data('location_type');
            let parent_station = $(this).data('parent_station');
            let stop_timezone = $(this).data('stop_timezone');
            let wheelchair_boarding = $(this).data('wheelchair_boarding');
            let level_id = $(this).data('level_id');
            let platform_code = $(this).data('platform_code');

            // console.log(stop_desc);
            $('#update_id').val(id);
            $('#stop-id').val(stop_id);
            $('#stop-code').val(stop_code);
            $('#stop-name').val(stop_name);
            $('#stop-desc').val(stop_desc);
            $('#stop-lat').val(stop_lat);
            $('#stop-lon').val(stop_lon);
            $('#zone-id').val(zone_id);
            $('#stop-url').val(stop_url);
            $('#stop-timezone').val(stop_timezone);
            $('#location_type').val(location_type);
            $('#parent_station').val(parent_station);
            $('#wheelchair-boarding').val(wheelchair_boarding);
            $('#level_id').val(level_id);
            $('#platform-code').val(platform_code);

            parent_station = '' + parent_station;
            level_id = '' + level_id;

            $('.parent_station_').select2({
                placeholder: parent_station,
                ajax: {
                    url: '/select2-stops-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.stop_id,
                                    id: item.stop_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.level_id_').select2({
                placeholder: level_id,
                ajax: {
                    url: '/select2-levels-ajax',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.level_id,
                                    id: item.level_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });


    </script>


    <script>
        var markers2 = [];


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({

            url: "/stop-map/",
            method: "get",
            data: {
                test: 'test',
            },
            success: function (data) {
                messageFlash(data, 'success');

                console.log(data);
                if(data[0]){
                    var tiles = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                            maxZoom: 18,
                            attribution: '<span style="border: solid 1px #1066b4;padding: 0 6px;"><span style="color: #000;font-weight: bold">Watri</span><span style="color: #003caf;font-weight: bold">Feed</span></span> <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
                        }),
                        latlng = L.latLng(data[0].stop_lat, data[0].stop_lon);

                    map = L.map('map', {center: latlng, zoom: 10, layers: [tiles]});

                    var markers = L.markerClusterGroup({chunkedLoading: true});

                    var addressPoints = [];
                    for (const stop of data) {
                        addressPoints.push([stop.stop_lat, stop.stop_lon, stop.stop_name, stop.stop_id]);
                    }
                    for (var i = 0; i < addressPoints.length; i++) {
                        var a = addressPoints[i];
                        // var title = "[ "+a[3]+" ] "+a[2];
                        var title = a[3];
                        if(isFloat(parseFloat(a[0]))&& isFloat(parseFloat(a[1]))){
                            var marker = L.marker(L.latLng(a[0], a[1]), { title: title });
                            marker.bindPopup(a[2]).on('click', clickZoom);
                            markers.addLayer(marker);
                            markers2.push(marker);
                        }

                    }


                    map.addLayer(markers);
                }

                // Here we might call the "hide" action 2 times, or simply set the "force" parameter to true:
                $("#map").LoadingOverlay("hide", true);


            },
            error: function (data) {
                const errors = $.parseJSON(data.responseText);
                let message = '';
                $.each(errors.errors, function (key, value) {
                    message += value + '<br>';
                });
                messageFlash(message, 'error');
            }
        });

        function clickZoom(e) {
            map.setView(e.target.getLatLng(), 15);
        }

        function markerFunction(id) {
            for (var i in markers2) {
                var markerID = markers2[i].options.title;
                var position = markers2[i].getLatLng();
                if (markerID == id) {
                    map.setView(position, 15);
                    if (!markers2[i]._icon) markers2[i].__parent.spiderfy();
                    markers2[i].openPopup();
                }
            }
        }

        $('document').ready(function () {
            // Custom
            var customElement = $("#w_l");

            // Let's call it 2 times just for fun...
            $("#map").LoadingOverlay("show", {
                image: "",
                fontawesome: "fa fa-cog fa-spin",
                fontawesomeColor: "#003caf",
                custom: customElement
            });

            function test (){
                $("tr").click(function () {
                    markerFunction($(this)[0].id);
                });

                $("#stops_data tr").click(function () {
                    var selected = $(this).hasClass("bg-success");
                    $("#stops_data tr").removeClass("bg-success");
                    if (!selected)
                        $(this).addClass("bg-success");
                });
            }

        });
        $("tr").click(function () {
            markerFunction($(this)[0].id);
        });

        $("#stops_data tr").click(function () {
            var selected = $(this).hasClass("bg-success");
            $("#stops_data tr").removeClass("bg-success");
            if (!selected)
                $(this).addClass("bg-success");
        });
        function isFloat(n){
            return Number(n) === n && n % 1 !== 0;
        }

    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/gtfs/partials/stops.blade.php ENDPATH**/ ?>