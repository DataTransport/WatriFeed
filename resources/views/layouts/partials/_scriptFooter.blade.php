<!-- Imported styles on this page -->
@yield('styles_page')

<!-- Bottom scripts (common) -->
<script src="/neon/js/gsap/TweenMax.min.js"></script>
<script src="/neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>


<script src="/neon/js/joinable.js"></script>
@if (isset($ajax_select))

@else

    <script src="/neon/js/resizeable.js"></script>
@endif

<script src="/neon/js/bootstrap.js"></script>
<script src="/neon/js/neon-api.js"></script>


<!-- Imported scripts on this page -->
@yield('scripts_page')

<!-- JavaScripts initializations and stuff -->
@yield('javascripts_init_stuff')
{!! app('html')->script('neon/js/neon-custom.js') !!}


<!-- Demo Settings -->
@yield('demo_settings')

