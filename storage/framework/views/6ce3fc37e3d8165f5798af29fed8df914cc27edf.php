
<div class="row" style="margin-bottom: 5px;">
    <div class="col-md-12" style="text-align: center; color: black; font-weight: bold; font-size: 14px">
       Your API-KEY : <span style="color: #158cea"><?php echo e(\Illuminate\Support\Facades\Auth::user()->key_api); ?></span>
    </div>
    <!-- Profile Info and Notifications -->
    <div class="col-md-6 col-sm-8 clearfix">

        <ul class="user-info pull-left pull-none-xsm">

            <!-- Profile Info -->
            <li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">


                    <strong style="color: #0078d8;font-size: 16px;"><?php echo e(Auth::user()->name); ?></strong>
                </a>

                <ul class="dropdown-menu">

                    <!-- Reverse Caret -->
                    <li class="caret"></li>

                    <!-- Profile sub-links -->
                    <li>
                        <a href="<?php echo e(route('users.edit')); ?>">
                            <i class="entypo-user"></i>
                            Edit Profile
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

    </div>



    <!-- Raw Links -->
    <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right">


            <li>


                <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <?php echo e(__('Log Out')); ?> <i class="entypo-logout right"></i>
                </a>

                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </li>
        </ul>

    </div>

</div>
<?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/layouts/partials/_header.blade.php ENDPATH**/ ?>