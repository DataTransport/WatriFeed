<?php
    $dashboard = 'active';
?>
<?php $__env->startSection('add_head'); ?>
    <?php echo app('html')->style('neon/css/font-icons/font-awesome/css/font-awesome.min.css'); ?>

    <style>
        .tile-stats .icon i {
            vertical-align: unset;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


    <div class="row" style="margin-top:65px;">
        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats tile-red" style="border-bottom: 1px solid #fff;background: #003471;color: white;">
                <div class="icon" style="color: #21a9e180"><i class="fa fa-database"></i></div>
                <div class="num" data-start="0" data-end="<?php echo e($gtfs_length); ?>" data-postfix="" data-duration="1500" data-delay="0">0</div>

                <h3>GTFS Registered</h3>
            </div>

        </div>

        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats" style="border-bottom: 1px solid #fff;background: #00a651;color: white;" >
                <div class="icon" style="color: #00000069"><i class="fa fa-map-marker"></i></div>
                <div class="num" data-start="0" data-end="<?php echo e($stops_length); ?>" data-postfix="" data-duration="1500" data-delay="600">0</div>

                <h3>Stops Registered</h3>
            </div>

        </div>

        <div class="clear visible-xs"></div>

        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats" style="background: #373e4a; border-bottom: 1px solid #ffffff; color: white;">
                <div class="icon" style="color: #1ed1d45e"><i class="fa fa-road"></i></div>
                <div class="num" data-start="0" data-end="<?php echo e($routes_length); ?>" data-postfix="" data-duration="1500" data-delay="1200">0</div>

                <h3>Routes Registered</h3>
            </div>

        </div>

        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats " style="background: #009689; border-bottom: 1px solid #ffffff; color: white;">
                <div class="icon" style="color: #00000069"><i class="fa fa-clock-o"></i></div>
                <div class="num" data-start="0" data-end="<?php echo e($stopTimes_length); ?>" data-postfix="" data-duration="1500" data-delay="1800">0</div>

                <h3>Stop Times Registered</h3>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">

        </div>

        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats " style="background: #732cbc; border-bottom: 1px solid #ffffff; color: white;">
                <div class="icon" style="color: #00000069"><i class="fa fa-exchange"></i></div>
                <div class="num" data-start="0" data-end="<?php echo e($trips_length); ?>" data-postfix="" data-duration="1500" data-delay="1800">0</div>

                <h3>Trips Registered</h3>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats " style="background: #009689; border-bottom: 1px solid #ffffff; color: white;">
                <div class="icon" style="color: #00000069"><i class="fa fa-code-fork"></i></div>
                <div class="num" data-start="0" data-end="<?php echo e($shapes_length); ?>" data-postfix="" data-duration="1500" data-delay="1800">0</div>

                <h3>Shapes Registered</h3>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/dashboard.blade.php ENDPATH**/ ?>