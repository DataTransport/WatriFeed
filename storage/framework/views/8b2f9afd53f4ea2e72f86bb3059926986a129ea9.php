<?php
    $list_users = 'active';
?>
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


    </style>
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
        <div style="border: 1px solid black;" class="panel panel-primary" data-collapsed="0">

            <div style="border-bottom: 1px solid #000000;" class="panel-heading">
                <div style="font-weight: bold;font-size: 18px; text-align: center"
                     class="col-sm-offset-2 col-sm-8 panel-title">
                    Users List
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered datatable col-sm-12" id="table-4">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Key</th>
                        <th>Etat</th>
                        <th>Creation Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                    ?>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr class="odd gradeX">
                            <td><?php echo e($loop->iteration); ?></td>
                            <td> <?php echo e($user->name); ?></td>
                            <td> <?php echo e($user->email); ?></td>
                            <td class="center"><?php echo e($user->key_api); ?></td>
                            <td class="center">
                                <?php if($user->state): ?>
                                    <i class="entypo-check" style="color: #00a000"></i>
                                <?php else: ?>
                                    <i class="entypo-cancel" style="color: #d90900"></i>
                                <?php endif; ?>
                            </td>
                            <td class="center"><?php echo e($user->created_at); ?></td>
                            <td>
                                <?php if((int)$user->id!==7 && (int)$user->id!==8 ): ?>
                                    <a data-name="<?php echo e($user->name); ?>" class="edit_gtfs btn btn-info btn-sm btn-icon icon-left"
                                       href="<?php echo e(url('reset_user',['id'=>$user->id])); ?>">
                                        <i class="entypo-arrows-ccw"></i>
                                        Reset Key
                                    </a>
                                <?php endif; ?>

                                <?php if(!$user->state): ?>
                                    <a href="<?php echo e(url('active_user',['id'=>$user->id])); ?>"
                                       class="btn btn-success btn-sm btn-icon icon-left">
                                        <i class="entypo-check"></i>
                                        Actived
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(url('active_user',['id'=>$user->id])); ?>"
                                       class="btn btn-danger btn-sm btn-icon icon-left">
                                        <i class="entypo-cancel"></i>
                                        Disabled
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal 6 (Long Modal)-->
    <div class="modal fade" id="modal-6">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Password</h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">

                            <form method="POST" action="<?php echo e(route('gtfs.check_pass.post')); ?> " id="form">
                                <?php echo csrf_field(); ?>
                                <div class="row">

                                    <div style="padding-left: 25px;"
                                         class="div_pass form-group  col-md-12 <?php echo $errors->has('password') ? 'has-error' : ''; ?>">
                                        <?php echo Form::password('password',['class' => 'form-control', 'placeholder' => 'GTFS-Password' ]); ?>

                                    </div>
                                    <input type="text" name="name" hidden>

                                </div>

                                <?php echo Form::submit('Submit', ['class' => 'btn_s btn btn-primary pull-right']); ?>

                                <span id="loading" style="display: none">loading...</span>
                            </form>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts_page'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/auth/user.blade.php ENDPATH**/ ?>