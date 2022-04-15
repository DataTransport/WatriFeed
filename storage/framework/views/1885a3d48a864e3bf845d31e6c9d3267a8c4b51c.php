<?php
    $list_gtfs = 'active';
    $edit_ ='edit_';
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

        .bg-success {

            box-shadow: 0px 0px 9px black !important;
            background-color: #053ba0 !important;
            color: #fff !important;
        }

        .bg-success td {
            color: #fff !important;
        }
    </style>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
    <script src="/Leaflet.PolylineOffset/leaflet.polylineoffset.js"></script>

    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
          rel='stylesheet'/>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
            <a href="<?php echo e(route('gtfs.edit', ['gtf' =>$gtfs->id ])); ?>" class="btn btn-primary">Back</a>
            <hr>
            <div style="border: 1px solid #3c1f6f;" class="panel panel-primary " data-collapsed="0">

                <div class="col-sm-1" style="padding-top: 10px;font-size: 18px;">
                    <i style="color: #fff;font-size: xx-large;" class="fa fa-code-fork"></i>
                </div>
                <div style="background: #3c1f6f; border-bottom: 1px solid #ffffff; color: white;" class="panel-heading"
                     class="panel-heading">
                    <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                        <div class="row">

                            <div class="col-sm-4">
                                Shapes
                            </div>

                            <div class="col-sm-4">
                                <form action="/shape" method="get">
                                    <input type="text" hidden value="<?php echo e(csrf_token()); ?>" name="t">
                                    <input type="text" hidden value="<?php echo e($gtfs->id); ?>" name="g">
                                    <div class="input-group" style="width: 200px;">
                                        <input type="text" class="form-control" name="search" placeholder="ShapeID">
                                        <span class="input-group-btn">
                                        <button class="btn btn-success" type="submit">
                                            <i style="color:#fff;" class="entypo-search"></i>
                                        </button>

                                        </span>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4">
                                <form action="/delete-shape" method="post">
                                    <?php echo csrf_field(); ?>
                                    
                                    <input type="text" hidden value="<?php echo e($gtfs->id); ?>" name="g">
                                    <div class="input-group" style="width: 200px;">
                                        <input type="text" class="form-control" name="id" placeholder="ShapeID"
                                               required>
                                        <span class="input-group-btn">
                                        <button class="btn btn-danger"
                                                onclick="return confirm('Are you sure you would like to delete this shape');"
                                                type="submit">
                                            <i style="color:#fff;" class="fa fa-trash"></i>
                                        </button>

                                        </span>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>


                    <div class="panel-options">
                <span class="badge badge-success" style="color: #000000; font-weight: bold; font-size: 10px">
                    Records <?php echo e($shapes->firstItem()); ?> - <?php echo e($shapes->lastItem()); ?> of <?php echo e($shapes->total()); ?> (for page <?php echo e($shapes->currentPage()); ?> )
                </span>
                        <a href="#" data-rel="collapse"><i style="color:#fff;" class="entypo-down-open"></i></a>
                        <a href="#add" id="add"><i style="color:#fff;" class="entypo-plus-circled"></i></a>

                    </div>


                </div>
                <div class="panel-body" style="">

                    <table class="table table-bordered datatable" id="shapes_data">
                        <thead>
                        <tr>
                            <th>ShapeID <span class="field_required">*</span></th>
                            <th>Pt_lat <span class="field_required">*</span></th>
                            <th>Pt_lon <span class="field_required">*</span></th>
                            <th>Pt_sequence <span class="field_required">*</span></th>
                            <th>ShapeDistTraveled</th>
                            <th>A</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $__currentLoopData = $shapes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shape): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="odd gradeX">
                                <td class="update <?php echo e($shape->id); ?>" data-id="<?php echo e($shape->id); ?>"
                                    data-column="shape_id"><?php echo e($shape->shape_id); ?></td>
                                <td class="update <?php echo e($shape->id); ?>" data-id="<?php echo e($shape->id); ?>"
                                    data-column="shape_pt_lat"><?php echo e($shape->shape_pt_lat); ?></td>
                                <td class="update <?php echo e($shape->id); ?>" data-id="<?php echo e($shape->id); ?>"
                                    data-column="shape_pt_lon"><?php echo e($shape->shape_pt_lon); ?></td>
                                <td class="update <?php echo e($shape->id); ?>" data-id="<?php echo e($shape->id); ?>"
                                    data-column="shape_pt_sequence"><?php echo e($shape->shape_pt_sequence); ?></td>
                                <td class="update <?php echo e($shape->id); ?>" data-id="<?php echo e($shape->id); ?>"
                                    data-column="shape_dist_traveled"><?php echo e($shape->shape_dist_traveled); ?></td>

                                <td>
                                    <button style="display: none;" type="button" name="save_btn"
                                            class="btn btn-success btn-sm save_btn<?php echo e($shape->id); ?> save_btn"
                                            data-rowid="<?php echo e($shape->id); ?>">save
                                    </button>
                                    <button type="button" name="edit_btn"
                                            class="btn btn-info btn-sm edit_btn<?php echo e($shape->id); ?> edit_btn"
                                            data-rowid="<?php echo e($shape->id); ?>"><i class="fa fa-edit"></i></button>
                                    <button type="button" name="delete" class="btn btn-danger btn-sm delete"
                                            id="<?php echo e($shape->id); ?>"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                        <th>ShapeId</th>
                        <th>Pt_lat</th>
                        <th>Pt_lon</th>
                        <th>Pt_sequence</th>
                        <th>ShapeDistTraveled</th>
                        <th></th>
                        </tfoot>
                    </table>

                    <?php echo e($shapes->links()); ?>



                </div>
            </div>
        </div>

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

    <?php echo app('html')->script('neon/js/select2/select2.min.js'); ?>

    <?php echo app('html')->script('neon/js/neon-chat.js'); ?>

    <?php echo app('html')->script('neon/js/toastr.js'); ?>



    <?php echo app('html')->script('dataTable/js/functions.js'); ?>

    <?php echo app('html')->script('dataTable/js/shapes.js'); ?>

    <script
        src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>





































































































































































































































































<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/gtfs/partials/shapes.blade.php ENDPATH**/ ?>