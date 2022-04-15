<?php
    $import_gtfs = 'active';
?>
<?php $__env->startSection('content'); ?>
    <div class="col-md-offset-2 col-md-8" style="margin-top:65px;">

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
        <div style="border: 1px solid black;" class="panel panel-primary" data-collapsed="0">

            <div style="border-bottom: 1px solid #000000;" class="panel-heading">
                <div style="font-weight: bold;font-size: 18px; text-align: center" class="col-sm-offset-2 col-sm-8 panel-title">
                    Import-GTFS
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <?php echo Form::open(['route' => 'gtfs.import', 'class' => 'form-horizontal panel','files' => true]); ?>

                <div class="row">
                    <div style="padding-left: 25px;" class="form-group col-md-offset-1 col-md-4 <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                        <label for="name" class="text-success">GTFS name</label>
                        <?php echo Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name-GTFS']); ?>

                    </div>
                    <div style="padding-left: 25px;padding-top: 5px;" class="form-group col-md-offset-1 col-md-6 <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                        <label for="name" class="text-success">GTFS file</label>
                        <?php echo Form::file('fileGtfs', null, ['class' => 'form-control', 'placeholder' => 'File-GTFS']); ?>

                    </div>
                </div>
                <div class="row">
                    <hr>
                    <div style="padding-left: 25px;" class="form-group  col-md-6 <?php echo $errors->has('password') ? 'has-error' : ''; ?>">
                        <label for="name" class="text-success">GTFS password</label>
                        <?php echo Form::password('password',['class' => 'form-control', 'placeholder' => 'GTFS-Password' ]); ?>

                    </div>
                    <div style="padding-left: 25px;" class="form-group col-md-6">
                        <label for="name" class="text-success">GTFS confirmation</label>
                        <?php echo Form::password('password_confirmation',['class' => 'form-control', 'placeholder' => 'Confirmation' ]); ?>

                    </div>
                </div>

                <?php echo Form::submit('Import', ['class' => 'btn btn-primary pull-right ']); ?>

                <?php echo Form::close(); ?>

            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('add_footer'); ?>
    <script !src="">

        console.log('toto');
        $( "form" ).submit(function( event ) {
            setTimeout(function(){ window.location = "https://watrifeed.ml/gtfs"; }, 10000);        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/gtfs/import.blade.php ENDPATH**/ ?>