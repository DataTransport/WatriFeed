<!-- Imported styles on this page -->
<?php echo $__env->yieldContent('styles_page'); ?>

<!-- Bottom scripts (common) -->
<script src="/neon/js/gsap/TweenMax.min.js"></script>
<script src="/neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>


<script src="/neon/js/joinable.js"></script>
<?php if(isset($ajax_select)): ?>

<?php else: ?>

    <script src="/neon/js/resizeable.js"></script>
<?php endif; ?>

<script src="/neon/js/bootstrap.js"></script>
<script src="/neon/js/neon-api.js"></script>


<!-- Imported scripts on this page -->
<?php echo $__env->yieldContent('scripts_page'); ?>

<!-- JavaScripts initializations and stuff -->
<?php echo $__env->yieldContent('javascripts_init_stuff'); ?>
<?php echo app('html')->script('neon/js/neon-custom.js'); ?>



<!-- Demo Settings -->
<?php echo $__env->yieldContent('demo_settings'); ?>

<?php /**PATH /Applications/MAMP/htdocs/laravel/watrifeed-php-v1/resources/views/layouts/partials/_scriptFooter.blade.php ENDPATH**/ ?>