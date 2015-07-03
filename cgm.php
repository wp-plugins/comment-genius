<?php
/*
Plugin Name: Comment Genius
Plugin URI: http://getbutterfly.com/wordpress-plugins-free/
Description: Comment Genius allows the user to create a customizable lightbox popup, suitable for Facebook Like/Share, Twitter Tweet, Google AdSense Ads and more. The popup is a pure textarea, in order to allow for source code copy/paste and it supports HTML code for custom text content.
Version: 1.2.4
Author: Ciprian Popescu
Author URI: http://getbutterfly.com/
*/

define('CGM_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('CGM_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('CGM_PLUGIN_VERSION', '1.2.4');

function cgm_init() {
    add_option('cg-enabled', 1);
    add_option('cg-genius-content', 'Your content here!');
    add_option('cg-genius-width', 300);
    add_option('cg-genius-height', 250);
    add_option('cg-genius-padding', 16);

    add_option('cg-title', 'Thank you for your contribution!');
    add_option('cg-opacity', '0.7');

    add_option('cg-overlay-close', true);
    add_option('cg-esc-close', true);
    add_option('cg-close', true);
}

add_action('init', 'cgm_init');

function cg_admin_menu() {
    add_options_page('Comment Genius', 'Comment Genius', 'manage_options', 'cgm', 'cgm');
}

function cgm() {
    global $current_screen;
    require_once(CGM_PLUGIN_PATH . '/cgm-options.php');
}

function cg_head() {
    wp_enqueue_style('colorbox', CGM_PLUGIN_URL . '/js/colorbox-master/colorbox.css');
    wp_print_styles('colorbox');

    wp_register_script('colorbox', CGM_PLUGIN_URL . '/js/colorbox-master/jquery.colorbox-min.js');
    wp_print_scripts(array('jquery', 'colorbox'));
}

function cg_admin_head() {
    cg_head();
}
function cg_wp_head() {
    cg_head();
}

add_action('admin_enqueue_scripts', 'cg_enqueue_color_picker');
function cg_enqueue_color_picker() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('cg-picker', plugins_url('js/cg-functions.js', __FILE__), array('wp-color-picker'), false, true);
}

function cg_post_redirect($location) {
    if(get_option('cg-enabled') != 1) {
        return $location;
    }

    cg_wp_head();
    ?>
    <a id="cg-genius-trigger" class="colorbox-popup-trigger" href="#"></a>
    <script>
    jQuery(document).ready(function(){
        jQuery(".colorbox-popup-trigger").colorbox({
            //width: <?php echo get_option('cg-genius-width'); ?>,
            //height: <?php echo get_option('cg-genius-height'); ?>,
            innerWidth: true,
            innerHeight: true,
            inline: true,
            href: "#popup_content",
            open: true,
            title: '<?php echo get_option('cg-title'); ?>',
            overlayClose: <?php echo get_option('cg-overlay-close'); ?>,
            escKey: <?php echo get_option('cg-esc-close'); ?>,
            closeButton: <?php echo get_option('cg-close'); ?>,
            onClosed: function() { window.location.replace('<?php echo $location; ?>') }
        });
        jQuery('#cg-genius-trigger').trigger('click');
    });
    </script>
    <div style="display:none">
        <div id="popup_content" style="width: <?php echo get_option('cg-genius-width'); ?>; height: <?php echo get_option('cg-genius-height'); ?>; padding: <?php echo get_option('cg-genius-padding'); ?>px; background-color: <?php echo get_option('cg-background-color'); ?>; color: <?php echo get_option('cg-text-color'); ?>;"><?php echo get_option('cg-genius-content') ?></div>
    </div>
    <?php
}

add_filter('comment_post_redirect', 'cg_post_redirect');
add_action('admin_menu', 'cg_admin_menu');
add_action('admin_head', 'cg_admin_head');
add_action('wp_head', 'cg_wp_head');
?>
