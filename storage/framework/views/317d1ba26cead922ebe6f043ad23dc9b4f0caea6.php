<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Collaborative web solution for GTFS multiple workflow management."/>
    <meta name="author" content="Data Transport"/>

    <link rel="icon" href="/images/WatriFeed_logo.png">

    <title>Watrifeed | Collaborative GTFS Multiple Workflow Management.</title>

    <?php echo app('html')->style('gtfs_frontend/css/bootstrap.css'); ?>

    <?php echo app('html')->style('gtfs_frontend/css/font-icons/entypo/css/entypo.css'); ?>

    <?php echo app('html')->style('gtfs_frontend/css/neon.css'); ?>



    <?php echo app('html')->script('gtfs_frontend/js/jquery-1.11.3.min.js'); ?>


    <!--[if lt IE 9]>
    <script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        @media (min-width: 1200px) {
            .container {
                width: 82%;
            }
        }

        .bar::before {
            content: '';
            position: absolute;
            left: 45%;;
            top: -2.7px;
            height: 10px;
            width: 10px;
            border-radius: 50%;
            background-color: #025ac1 !important;
            -webkit-animation-duration: 3s;
            animation-duration: 3s;
            -webkit-animation-timing-function: linear;
            animation-timing-function: linear;
            -webkit-animation-iteration-count: infinite;
            animation-iteration-count: infinite;
            -webkit-animation-name: MOVE-BG;
            animation-name: MOVE-BG;
        }

        .bar2::before {
            content: '';
            position: absolute;
            left: 45%;;
            top: -2.7px;
            height: 10px;
            width: 10px;
            border-radius: 50%;
            background-color: #f44336 !important;
            -webkit-animation-duration: 3s;
            animation-duration: 3s;
            -webkit-animation-timing-function: linear;
            animation-timing-function: linear;
            -webkit-animation-iteration-count: infinite;
            animation-iteration-count: infinite;
            -webkit-animation-name: MOVE-BG;
            animation-name: MOVE-BG;
        }

        .dropdown-menu a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-menu a:hover {background-color: #ddd;}

        .btn-group:hover .dropdown-menu {display: block;}

        .btn-group:hover .dropbtn {background-color: #3e8e41;}

    </style>


</head>
<body>

<div class="wrap">

    <?php if(\Illuminate\Support\Facades\Session::has('message')): ?>

        <div class="alert alert-info"
             style="
             font-size: 18px;font-weight: bold;text-align: center; color:#0b74c7;"
        ><?php echo e(\Illuminate\Support\Facades\Session::get('message')); ?></div>

<?php endif; ?>
<!-- Logo and Navigation -->
    <div class="site-header-container container" style="width: 80%">

        <div class="row">

            <div class="col-md-12">

                <header class="site-header">

                    <section class="site-logo" style="padding-top: 0px; padding-bottom: 10px;">

                        <a href="#">
                            <img src="" width="120"/>
                            <h3 style="font-size: 59px;margin-bottom: 0px;font-family: unset;text-align: center;">
                                Watri<span style="color: #025ac1;">Feed</span></h3>
                            <hr style="margin-top: 0; margin-bottom: 4px;border: 0;border-top: 1px solid #ff0000;">
                            <span style="font-size: 11px; color: #025ac1; font-weight: bold;">GTFS Multiple Workflow Management | API | <span
                                    style="color: #f91000">Transit</span><span style="color: black">Viz</span></span>
                        </a>


                    </section>

                    <nav class="site-nav">

                        <ul class="main-menu hidden-xs" id="main-menu">
                            <li class="active">
                                <a href="<?php echo e(url('/')); ?>">
                                    <span>Home</span>
                                </a>
                            </li>
                            <div class="btn-group">
                                <button type="button" class="dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" >
                                    Docs
                                </button>
                                <div class="dropdown-menu" style="padding: 10px;min-width: 220px;">
                                    <a class="dropdown-item" target="_blank"
                                       href="https://wiki.openstreetmap.org/wiki/WatriFeed-Collaborative_web_solution_for_GTFS_multiple_workflow_management.">
                                        WatriFeed GTFS Manager
                                    </a>
                                    <a class="dropdown-item" target="_blank"
                                       href="<?php echo e(url('/watrifeed-api')); ?>">
                                        WatriFeed-API
                                    </a>
                                </div>
                            </div>
                            <li class="">
                                <a href="<?php echo e(url('/watrifeed-api')); ?>">
                                    <span
                                        style="text-transform: none;font-weight: bold; font-size: 16px">WatriFeed-API</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="https://www.data-transport.org" target="_blank">
                                    <span
                                        style="text-transform: none;font-weight: bold; font-size: 16px">Data-Transport</span>
                                </a>
                            </li>

                            <li>
                                <?php if(Route::has('login')): ?>
                                    <div class="top-right links">
                                        <?php if(auth()->guard()->check()): ?>

                                            <a class="btn btn-primary" href="<?php echo e(url('/dashboard')); ?>">GTFS-MANAGER </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                <?php echo e(__('Log Out')); ?> <i class="entypo-logout right"></i>
                                            </a>

                                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                                  style="display: none;">
                                                <?php echo csrf_field(); ?>
                                            </form>
                                        <?php else: ?>

                                            <a href="<?php echo e(route('login')); ?>">Login</a>

                                            <?php if(Route::has('register')): ?>
                                                &nbsp;&nbsp;
                                                <a href="<?php echo e(route('register')); ?>">Register</a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>
                            </li>


                        </ul>


                        <div class="visible-xs">

                            <a href="#" class="menu-trigger">
                                <i class="entypo-menu"></i>
                            </a>

                        </div>
                    </nav>

                </header>

            </div>

        </div>

    </div>
    <!-- Main Slider -->
    <section class="slider-container"
             style="background-image: url(<?php echo e(asset('gtfs_frontend/images/slide-img-1-bg.png')); ?>);">

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <div class="slides">

                        <!-- Slide 1 -->
                        <div class="slide">

                            <div class="slide-content">
                                <h2>
                                    <small>WatriFeed-API</small>
                                    Build and develop mobility Apps

                                </h2>

                                <p>
                                    Design and build your own Apps using the WatriFeed-API.
                                </p>
                            </div>

                            <div class="slide-image">

                                <a href="#">
                                    <img height="300" src="<?php echo e(asset('gtfs_frontend/images/architecture.png')); ?>"
                                         class="img-responsive"/>
                                </a>
                            </div>

                        </div>

                        <!-- Slide 2 -->
                        <div class="slide" data-bg="<?php echo e(asset('gtfs_frontend/images/slide-img-1.png')); ?>">

                            <div class="slide-image">

                                <a href="#">
                                    <img src="<?php echo e(asset('gtfs_frontend/images/test.png')); ?>" class="img-responsive"/>
                                </a>
                            </div>

                            <div class="slide-content text-right">
                                <h2>
                                    <small>Building</small>

                                    Create your GTFS Data feed using easily.
                                </h2>

                                <p>

                                </p>

                            </div>

                        </div>

                        <!-- Slide 3 -->
                        <div class="slide">

                            <div class="slide-content">
                                <h2>
                                    <small>Visualize</small>
                                    Visualize your GTFS Data feed
                                </h2>

                                <p>
                                    TransitViz is a perfect data visualization soft.
                                </p>
                            </div>

                            <div class="slide-image">

                                <a href="#">
                                    <img src="<?php echo e(asset('/images/gtfs_appmobile.png')); ?>" class="img-responsive"/>
                                </a>
                            </div>

                        </div>

                        <!-- Slider navigation -->
                        <div class="slides-nextprev-nav">
                            <a href="#" class="prev">
                                <i class="entypo-left-open-mini"></i>
                            </a>
                            <a href="#" class="next">
                                <i class="entypo-right-open-mini"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>
    <!-- Features Blocks -->
    <hr style="border-top: 1px solid #0e5ac1;">
    <section class="features-blocks">

        <div class="container">

            <div class="row vspace"><!-- "vspace" class is added to distinct this row -->

                <div class="col-md-6">

                    <div class="feature-block">
                        <img style="max-width: 100%;height: auto;
                        box-shadow: 0 7px 27px 0 rgba(2, 90, 193, 0.48);
" src="/images/watrifeed_description_image.jpg" alt="">
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="feature-block" style="padding-left:0;">
                        <h3 style="text-align:center;text-transform:initial;font-weight:bold;font-size: 18px;padding-left:0;">
                            Watri<span style="color: #025ac1;">Feed</span> GTFS MANAGER
                        </h3>
                        <div id="bar" class="bar" style="height: 5px;
                                                width: 90px;
                                                background: rgba(2,90,193,0.55);
                                                margin: 20px auto;
                                                position: relative;
                                                border-radius: 30px;"></div>

                        <div style="font-size: 17px;font-family: 'Poppins', sans-serif;">
                            <p style=" color: #000;text-align: justify;padding-left:0;">
                                WatriFeed-GTFS-Manager: The OpenSource Collaborative GTFS Data Editor.
                                WatriFeed is a collaborative web editor for GTFS data developed by the Data Transport
                                project labs to standardize transport data for easy use in transport and mobility
                                applications and services. The editor is online and one instance is available for local
                                deployment and use without
                                the Internet.
                            </p>

                        </div>
                        <div>
                            <a href="https://watrifeed.ml/register" class="btn btn-info"
                               style="box-shadow: 0 13px 27px 0 rgba(2,90,193,0.25);font-weight: 600;
                                    font-size: 14px;
                                    margin-top: 20px;
                                    border: none;
                                    padding: 15px 40px;
                                    position: relative;
                                    border-radius: 4px;
                                    z-index: 1;
                                    text-transform: uppercase;
                                    -webkit-transition: 0.5s;
                                    transition: 0.5s;
                                    background-color: #0e5ac1;
                                    "
                            >Register</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </section>
    <hr style="border-top: 1px solid #0e5ac1;">
    <section class="features-blocks">

        <div class="container">

            <div class="row vspace"><!-- "vspace" class is added to distinct this row -->

                <div class="col-sm-4">

                    <div class="feature-block">
                        <h3>
                            <i class="entypo-cog" style="color: #025ac1"></i>
                            Create
                        </h3>

                        <p style="font-size: 15px; color: black; text-align: justify">
                            Alone or in a team, create your GTFS feed easily. GTFS multiple workflow management.
                            Manage multiple GTFS workflows

                        </p>
                    </div>

                </div>

                <div class="col-sm-4">

                    <div class="feature-block">
                        <h3>
                            <i class="entypo-gauge" style="color: #025ac1"></i>
                            Testing and import
                        </h3>

                        <p style="font-size: 15px; color: black; text-align: justify">
                            Import your data from OpenStreetMap for GTFS creation/conversion.
                            Import and update your GTFS feed quickly.

                        </p>
                    </div>

                </div>

                <div class="col-sm-4">

                    <div class="feature-block">
                        <h3>
                            <i class="entypo-lifebuoy" style="color: #025ac1"></i>
                            Visualization and Sharing
                        </h3>

                        <p style="font-size: 15px; color: black; text-align: justify">
                            Validate, then share your GTFS feed for immediate use.
                            Visualize your data with an embedded GTFS Data Visualizer.
                            Assert quality control, commit, then share your GTFS feed for instant utilization
                        </p>
                    </div>

                </div>

            </div>

            <!-- Separator -->
            <div class="row">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>

        </div>

    </section>
    <hr style="border-top: 1px solid #0e5ac1;">
    <section class="features-blocks">

        <div class="container">

            <div class="row vspace"><!-- "vspace" class is added to distinct this row -->

                <div class="col-md-6">

                    <div class="feature-block">
                        <img style="max-width: 100%;height: auto;

                        box-shadow: 0 7px 27px 0 rgba(35, 35, 35, 0.39);

" src="/images/transitviz_description_image.jpg"
                             alt="">
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="feature-block" style="padding-left:0;">
                        <h3 style="text-align:center;text-transform:initial;font-weight:bold;font-size: 18px;padding-left:0;">
                            <span style="color:#f91000"> Transit</span><span style="color: #000;">Viz</span> Data
                            Visualization
                        </h3>
                        <div id="bar" class="bar2" style="height: 5px;
                                                width: 90px;
                                                background: rgba(244, 67, 54, 0.57);
                                                margin: 20px auto;
                                                position: relative;
                                                border-radius: 30px;"></div>

                        <div style="font-size: 17px;font-family: 'Poppins', sans-serif;">
                            <p style=" color: #000;text-align: justify;padding-left:0;">
                                Digital transportation data mining and analysis through graphs, interactive web maps is
                                an excellent way to tackle the growing complexity of the available data, and to learn
                                from it in a unifying way.
                                We have a dedicated application named TransitViz, exclusively dedicated to the
                                visaulisation of transport data.
                            </p>
                        </div>
                        <div>
                            <a href="https://transitviz.org" target="_blank" class="btn btn-info"
                               style="box-shadow: 0 13px 27px 0 rgba(249,16,0,0.25);font-weight: 600;
                                    font-size: 14px;
                                    margin-top: 30px;
                                    border: none;
                                    padding: 15px 40px;
                                    position: relative;
                                    border-radius: 4px;
                                    z-index: 1;
                                    text-transform: uppercase;
                                    -webkit-transition: 0.5s;
                                    transition: 0.5s;
                                    background-color: #d41000;
                                    "
                            >Access TransitViz</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </section>
    <hr style="border-top: 1px solid #0e5ac1;">
    <section class="features-blocks">

        <div class="container">

            <div class="row vspace"><!-- "vspace" class is added to distinct this row -->

                <div class="col-md-6">

                    <div class="feature-block">
                        <img style="max-width: 100%;height: auto;
                        box-shadow: 0 7px 27px 0 rgba(2, 90, 193, 0.48);
" src="/images/transitviz_api_description_image.jpg"
                             alt="">
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="feature-block" style="padding-left:0;">
                        <h3 style="text-align:center;text-transform:initial;font-weight:bold;font-size: 18px;padding-left:0;">
                            <span style="color:#000"> Watri</span><span style="color: #0e5ac1;">Feed-API</span>
                            | Mobile & Web Application Development
                        </h3>
                        <div id="bar" class="bar" style="height: 5px;
                                                width: 90px;
                                                background: rgba(2,90,193,0.55);
                                                margin: 20px auto;
                                                position: relative;
                                                border-radius: 30px;"></div>

                        <div style="font-size: 17px;font-family: 'Poppins', sans-serif;">
                            <p style=" color: #000;text-align: justify;padding-left:0;">
                                WatriFeed-API provides a Application Programming Interface (API) which allows developers
                                to
                                build their own applications using specific transport data.

                                We also supports enterprises, organisations and institutions in designing and developing
                                web and mobile applications for mobility services
                            </p>
                        </div>
                        <div>
                            <a target="_blank" href="https://watrifeed.ml/watrifeed-api" class="btn btn-info"
                               style="box-shadow: 0 13px 27px 0 rgba(2,90,193,0.25);font-weight: 600;
                                    font-size: 14px;
                                    margin-top: 20px;
                                    border: none;
                                    padding: 15px 40px;
                                    position: relative;
                                    border-radius: 4px;
                                    z-index: 1;
                                    text-transform: uppercase;
                                    -webkit-transition: 0.5s;
                                    transition: 0.5s;
                                    background-color: #0e5ac1;
                                    "
                            >Access WatriFeed-API</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
    <hr style="border-top: 1px solid #0e5ac1;">
    <!-- Testimonails -->
    <section class="testimonials-container">

        <div class="container">

            <div class="col-md-12">

                <div class="testimonials carousel slide" data-interval="8000">

                    <div class="carousel-inner">

                        <div class="item active">

                            <blockquote>
                                <p>


                                    Collaborative solution powered by Data-transport for standardize . <br/>
                                    transportation data in a simple and user-friendly process.
                                </p>
                                <small>
                                    <cite>Data-transport</cite> - Labs
                                </small>
                            </blockquote>

                        </div>

                        <div class="item">

                            <blockquote>
                                <p>
                                    Let us no longer wait to re-imagine transportation and mobility by using data.
                                    <br/>
                                </p>
                                <small>
                                    <cite>Data-transport</cite> - Labs
                                </small>
                            </blockquote>

                        </div>

                        <div class="item">

                            <blockquote>
                                <p>
                                    Let's take watrifeed's power. <br/>
                                    <br/>

                                </p>
                                <small>
                                    <cite>Data-transport</cite> - Labs
                                </small>
                            </blockquote>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- Client Logos -->
    <section class="clients-logos-container">

        <div class="container">

            <div class="row">

                <div class="client-logos carousel slide" data-ride="carousel" data-interval="5000">

                    <div class="carousel-inner">

                        <div class="item active">

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/data trans.png')); ?>"/>
                            </a>

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/billetExpress_Mali.png')); ?>"/>
                            </a>

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/ztech.png')); ?>"/>
                            </a>

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/icrisat.png')); ?>"/>
                            </a>

                        </div>

                        <div class="item">

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/data trans.png')); ?>"/>
                            </a>

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/billetExpress_Mali.png')); ?>"/>
                            </a>

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/ztech.png')); ?>"/>
                            </a>

                            <a href="#">
                                <img src="<?php echo e(asset('gtfs_frontend/images/icrisat.png')); ?>"/>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
    <!-- Footer Widgets -->
    <section class="footer-widgets" style="border-top: 1px solid #ff4e50;">

        <div class="container">

            <div class="row">

                <div class="col-sm-6">

                    <a href="#">
                        <span>Watrifeed</span>
                    </a>

                    <p style="font-size: 17px;">
                        Collaborative web solution for GTFS multiple workflow management. <br/>

                    </p>

                </div>

                <div class="col-sm-3">

                    <h5>Address</h5>

                    <p style="font-size: 17px;">
                        Golf, Bamako, Mali. <br/>

                    </p>

                </div>

                <div class="col-sm-3">

                    <h5>Contact</h5>

                    <p style="font-size: 17px;">
                        Phone 1: +223 73 67 84 23 <br/>
                        Phone 2: +223 79 51 02 70 <br/>
                        labs@data-transport.org
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- Site Footer -->
    <footer class="site-footer" style="background: #000000;padding: 30px 0;color: white;">

        <div class="container">

            <div class="row">

                <div class="col-sm-6">
                    Copyright &copy; WatriFeed - All Rights Reserved.
                </div>

                <div class="col-sm-6">

                    <ul class="social-networks text-right">
                        <li>
                            <a href="#">
                                <i class="entypo-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="entypo-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="entypo-facebook"></i>
                            </a>
                        </li>
                    </ul>

                </div>

            </div>

        </div>

    </footer>
</div>


<!-- Bottom scripts (common) -->





<?php echo app('html')->script('gtfs_frontend/js/gsap/TweenMax.min.js'); ?>

<?php echo app('html')->script('gtfs_frontend/js/bootstrap.js'); ?>

<?php echo app('html')->script('gtfs_frontend/js/joinable.js'); ?>

<?php echo app('html')->script('gtfs_frontend/js/resizeable.js'); ?>

<?php echo app('html')->script('gtfs_frontend/js/neon-slider.js'); ?>


<!-- JavaScripts initializations and stuff -->
<?php echo app('html')->script('gtfs_frontend/js/neon-custom.js'); ?>



</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/frontend.blade.php ENDPATH**/ ?>