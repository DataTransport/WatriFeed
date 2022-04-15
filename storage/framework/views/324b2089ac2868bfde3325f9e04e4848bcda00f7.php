;

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
                    Edit <?php echo e($gtfs->name); ?>

                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <?php echo Form::open(['route' => ['gtfs.update',$gtfs->id], 'class' => 'form form-horizontal panel']); ?>

                <div class="row">

                    <div style="padding-left: 25px;" class="form-group col-md-3 <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                        Nom
                        <?php echo Form::text('name', $gtfs->name , ['class' => 'form-control']); ?>

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
                        <?php echo Form::submit('Valider', ['class' => 'btn btn-primary pull-right form-control btn_s']); ?>

                        <?php echo Form::close(); ?>

                    </div>
                </div>



            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts_page'); ?>
    <script>
        function messageFlash(message,type="success") {
            let opts = {
                "closeButton": true,
                "debug": false,
                "positionClass": rtl() || public_vars.$pageContainer.hasClass('right-sidebar') ? "toast-top-left" : "toast-top-right",
                "toastClass": "red",
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            if (type === 'success'){
                toastr.success(message,opts);
            }else if(type === 'info'){
                toastr.info(message,opts);
            }else {
                toastr.error(message,opts);
            }


        }
        $('.form').on('submit',function (e) {
            e.preventDefault();

            c = 'ok';

            let data = $(this).serialize();
            let url = $(this).attr('action');
            // let url = url;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: 'PUT',
                url: url,
                data: data,
                success: function (data) {

                    // data = $.parseJSON(data);
                    console.dir(data);

                    // if(data.response==='ok'){

                        $('.btn_s').val('Wait...');
                        messageFlash('GTFS update');
                        document.location.href = '/gtfs/';


                    // }else {
                    //     messageFlash('Password incorrect','error');
                    //     let btn = $('.btn_s');
                    //     btn.removeClass('disabled');
                    //     btn.val('Submit');
                    // }


                },
                error:function (data) {
                    const errors = $.parseJSON(data.responseText);
                    let message='';
                    console.dir(errors.errors);
                    $.each(errors.errors, function (key, value) {
                        message+=value+'<br>';
                    });
                    // console.log(errors.errors.password[0]);
                    messageFlash(message,'error');
                    $('.div_pass').addClass('has-error');
                    // alertify.error(message);
                    return false;

                    // messageFlash(message,'error');
                }
            })
        })
    </script>
    <?php echo app('html')->script('neon/js/toastr.js'); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/gtfs/settings.blade.php ENDPATH**/ ?>