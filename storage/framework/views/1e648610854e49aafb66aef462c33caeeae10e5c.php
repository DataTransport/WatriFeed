
<div class="sidebar-menu">

    <div class="sidebar-menu-inner">

        <header class="logo-env">

            <!-- logo -->
            <div class="logo">
                <a href="<?php echo e(url('/')); ?>" style="width: 163px;position: relative;top: -23px;">
                    <h3 style="color: #fff; font-size: 34px;margin-bottom: 0px;font-family: unset;text-align: center;">Watri<span style="color: #31bfff;">Feed</span></h3>
                    <hr style="margin-top: 0px; margin-bottom: 4px;border: 0;border-top: 1px solid #ff0000;">
                    <span style="font-size: 9px; color: #31bfff; font-weight: bold;">GTFS Multiple Workflow Management.</span>
                </a>
            </div>

            <!-- logo collapse icon -->
            <div class="sidebar-collapse">
                <a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                    <i class="entypo-menu"></i>
                </a>
            </div>


            <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
            <div class="sidebar-mobile-menu visible-xs">
                <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                    <i class="entypo-menu"></i>
                </a>
            </div>

        </header>


        <ul id="main-menu" class="main-menu">
            <!-- add class "multiple-expanded" to allow multiple submenus to open -->
            <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->

            <li>
                <a href="<?php echo e(url('/')); ?>" >
                    <i class="entypo-home"></i>
                    <span class="title">Home</span>
                </a>
            </li>
            <li class="<?php echo e($dashboard ?? ''); ?>">
                <a href="<?php echo e(url('/dashboard')); ?>" >
                    <i class="entypo-gauge"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo e($new_gtfs ?? ''); ?>">
                <a href="<?php echo e(url('/gtfs/create')); ?>" >
                    <i class="entypo-plus-circled"></i>
                    <span class="title">New-GTFS</span>
                </a>
            </li>
            <li class="<?php echo e($import_gtfs ?? ''); ?>">
                <a href="<?php echo e(url('/import')); ?>" >
                    <i class="entypo-down"></i>
                    <span class="title">Import-GTFS</span>
                </a>
            </li>
            <li class="<?php echo e($list_gtfs ?? ''); ?>">
                <a href="<?php echo e(url('/gtfs')); ?>" >
                    <i class="entypo-list"></i>
                    <span class="title">GTFS-List</span>
                </a>
            </li>

            <?php if((int)\Illuminate\Support\Facades\Auth::id()===1): ?>
                <li class="<?php echo e($list_users ?? ''); ?>">
                    <a href="<?php echo e(url('/user')); ?>" >
                        <i class="entypo-users"></i>
                        <span class="title">List-Users</span>
                    </a>
                </li>
            <?php endif; ?>





        </ul>

    </div>

</div>
<?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/layouts/partials/_menu.blade.php ENDPATH**/ ?>