<?php
    $new_gtfs ='active'
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
                <div style="font-weight: bold;font-size: 18px; text-align: center"
                     class="col-sm-offset-2 col-sm-8 panel-title">
                    create new GTFS
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <?php echo Form::open(['route' => 'gtfs.store', 'class' => 'form-horizontal panel','files' => true]); ?>

                <div class="row">
                    <div style="padding-left: 25px;"
                         class="form-group col-md-12 <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                        <label for="name" class="text-success">GTFS name</label>
                        <?php echo Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'GTFS-Name','id'=>'name']); ?>

                    </div>

                    <div style="padding-left: 25px;"
                         class="form-group  col-md-6 <?php echo $errors->has('password') ? 'has-error' : ''; ?>">
                        <label for="name" class="text-success">GTFS password</label>
                        <?php echo Form::password('password',['class' => 'form-control', 'placeholder' => 'GTFS-Password' ]); ?>

                    </div>
                    <div style="padding-left: 25px;" class="form-group col-md-6">
                        <label for="name" class="text-success">Confirmation</label>
                        <?php echo Form::password('password_confirmation',['class' => 'form-control', 'placeholder' => 'Confirmation' ]); ?>

                    </div>

                </div>

                <?php echo Form::submit('Create', ['class' => 'btn btn-primary pull-right']); ?>

                <?php echo Form::close(); ?>

            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/gtfs/create.blade.php ENDPATH**/ ?>