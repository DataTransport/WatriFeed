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

    </style>


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

            <div style="border: 1px solid #000d58;" class="panel panel-primary " data-collapsed="0">

                <div style="background: #000d58;color: white;border: 1px solid white;" class="panel-heading">
                    <div class="col-sm-2" style="padding-top: 10px;font-size: 18px;">
                        <i style="color: #fff;font-size: xx-large;" class="fa fa-home"></i>
                    </div>
                    <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-8 panel-title">
                        agencies.txt
                    </div>

                    <div class="panel-options">
                        <span class="badge badge-success"
                              style="color: #000000; font-weight: bold; font-size: 10px"><?php echo e($agencies->count()); ?></span>
                        <a href="#" data-rel="collapse"><i style="color: #fff;" class="entypo-down-open"></i></a>
                        <a href="#" name="add" id="add1">
                            <span style="color: #fff">insert</span>
                            <i style="color: #fff;" class="entypo-plus-circled"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">

                    <div id="alert_message"></div>

                    <table class="table table-bordered datatable table-dark" id="agencies_data">
                        <thead>
                        <tr class="replace-inputs">
                            <th>ID</th>
                            <th>Name <span class="field_required">*</span></th>
                            <th>Url <span class="field_required">*</span></th>
                            <th>Timezone <span class="field_required">*</span></th>
                            <th>Lang</th>
                            <th>Phone</th>
                            <th>Fare url</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="odd gradeX">
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_id"><?php echo e($agency->agency_id); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_name"><?php echo e($agency->agency_name); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_url"><?php echo e($agency->agency_url); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_timezone"><?php echo e($agency->agency_timezone); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_lang"><?php echo e($agency->agency_lang); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_phone"><?php echo e($agency->agency_phone); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_fare_url"><?php echo e($agency->agency_fare_url); ?></td>
                                <td class="update <?php echo e($agency->id); ?>" data-id="<?php echo e($agency->id); ?>"
                                    data-column="agency_email"><?php echo e($agency->agency_email); ?></td>
                                <td>
                                    <button style="display: none;" type="button" name="save_btn"
                                            class="btn btn-success btn-sm save_btn<?php echo e($agency->id); ?> save_btn"
                                            data-rowid="<?php echo e($agency->id); ?>">save
                                    </button>
                                    <button type="button" name="edit_btn"
                                            class="btn btn-info btn-sm edit_btn<?php echo e($agency->id); ?> edit_btn"
                                            data-rowid="<?php echo e($agency->id); ?>"><i class="fa fa-edit"></i></button>
                                    <button type="button" name="delete" class="btn btn-danger btn-sm delete"
                                            id="<?php echo e($agency->id); ?>"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <?php if($agencies->count()>0): ?>
                            <tfoot>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Url</th>
                            <th>Timezone</th>
                            <th>Lang</th>
                            <th>Phone</th>
                            <th>Fare url</th>
                            <th>Email</th>
                            <th>A</th>
                            </tfoot>
                        <?php endif; ?>

                    </table>
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

    <?php echo app('html')->script('dataTable/js/agencies.js'); ?>

    <script>
        $( document ).ready(function() {
            <?php if($agencies->count()===0): ?>
            add_row();
            <?php endif; ?>
        });

    </script>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/gtfs/partials/agencies.blade.php ENDPATH**/ ?>