<?php $__env->startSection('content'); ?>
    <div class=" col-md-12">

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
                    Edit user
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <?php echo Form::open(['route' => ['users.update',$user], 'class' => 'form-horizontal panel']); ?>

                <div class="row">

                    <div style="padding-left: 25px;" class="form-group col-md-3 <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                        Nom
                        <?php echo Form::text('name', $user->name , ['class' => 'form-control']); ?>

                    </div>
                    <div style="padding-left: 25px;padding-top: 0px;" class="form-group col-md-3 <?php echo $errors->has('email') ? 'has-error' : ''; ?>">
                        Email
                        <?php echo Form::email('email', $user->email, ['class' => 'form-control' ]); ?>

                    </div>
                    <div style="padding-left: 25px;" class="form-group  col-md-3 <?php echo $errors->has('password') ? 'has-error' : ''; ?>">
                        Password
                        <?php echo Form::password('password',['class' => 'form-control' ]); ?>

                    </div>
                    <div style="padding-left: 25px;" class="form-group col-md-3">
                        Confirmation
                        <?php echo Form::password('password_confirmation',['class' => 'form-control' ]); ?>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-5"></div>
                    <div style="padding-left: 10px;" class="form-group col-md-2 ">
                        <?php echo Form::submit('Valider', ['class' => 'btn btn-primary pull-right form-control']); ?>

                        <?php echo Form::close(); ?>

                    </div>
                </div>



            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/users/edit.blade.php ENDPATH**/ ?>