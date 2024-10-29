<?php

/*
 *  Plugin Name: SwiftStaff
 *  Plugin URL: https://SwiftCRM.com
 *  Description: SwiftStaff
 *  Version: 2.1
 *  Author: Roger Vaughn, Tejas Hapani
 *  Author URI: https://SwiftCRM.com
 *  Text Domain: swift_staff
 */

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    _e('Hi there!  I\'m just a plugin, not much I can do when called directly.', 'swift_staff');
    exit;
}

define('SWIFTSTAFF_VERSION', '2.1');
define('SWIFTSTAFF_MINIMUM_WP_VERSION', '5.7');
define('SWIFTSTAFF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SWIFTSTAFF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SWIFTSTAFF_PLUGIN_PREFIX', 'swift_staff_');

register_activation_hook(__FILE__, 'swift_staff_install');
if (!function_exists('swift_staff_install')) {

    function swift_staff_install() {
        if (version_compare($GLOBALS['wp_version'], SWIFTSTAFF_MINIMUM_WP_VERSION, '<')) {
            add_action('admin_notices', 'swift_staff_version_admin_notice');

            function swift_staff_version_admin_notice() {
                echo '<div class="notice notice-error is-dismissible sc-admin-notice"><p>' . sprintf(esc_html__('Swift Staff %s requires WordPress %s or higher.', 'swift_staff'), SWIFTSTAFF_VERSION, SWIFTSTAFF_MINIMUM_WP_VERSION) . '</p></div>';
            }

            add_action('admin_init', 'swift_staff_deactivate_self');

            function swift_staff_deactivate_self() {
                if (isset($_GET["activate"]))
                    unset($_GET["activate"]);
                deactivate_plugins(plugin_basename(__FILE__));
            }

            return;
        }
        update_option('swift_staff_version', SWIFTSTAFF_VERSION);
        
        if (!wp_next_scheduled('swift_staff_api_post')) {
            wp_schedule_event(time(), 'hourly', 'swift_staff_api_post');
        }
    }

}

require_once 'admin/swift-staff-admin.php';
require_once 'swift-staff-pagetemplater.php';
require_once 'public/section/swift-staff-pre-load-data.php';

/**
 *       plugin load
 * */
add_action('wp_loaded', 'swift_staff_update_check_callback');
if (!function_exists('swift_staff_update_check_callback')) {

    function swift_staff_update_check_callback() {
        if (get_option("swift_staff_version") != SWIFTSTAFF_VERSION) {
            swift_staff_install();
            swift_staff_pre_load_data();
        }
    }

}

/**
 *      Deactive plugin
 */
register_deactivation_hook(__FILE__, 'swift_staff_deactive_plugin');
if (!function_exists('swift_staff_deactive_plugin')) {

    function swift_staff_deactive_plugin() {
        flush_rewrite_rules();
    }

}

/**
 *      Uninstall plugin
 */
register_uninstall_hook(__FILE__, 'swift_staff_uninstall_callback');
if (!function_exists('swift_staff_uninstall_callback')) {

    function swift_staff_uninstall_callback() {
        global $wpdb;

        wp_clear_scheduled_hook('swift_staff_api_post');
        delete_option("swift_staff_version");
        delete_option("swift_staff_notice");

        // delete pages
        $pages = get_option('swift_staff_pre_load_pages');
        if ($pages) {
            $pages = explode(",", $pages);
            foreach ($pages as $pid) {
                wp_delete_post($pid, true);
            }
        }
        delete_option("swift_staff_pre_load_pages");

        /**
         * Delete CPT swift_jobs and terms
         */
        // Delete Taxonomy
        foreach (array('swift_jobs_category') as $taxonomy) {
            $wpdb->delete(
                    $wpdb->term_taxonomy, array('taxonomy' => $taxonomy)
            );
        }

        // Delete CPT posts
        $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type IN ('swift_jobs', 'swift_staffs')");
        $wpdb->query("DELETE meta FROM $wpdb->postmeta meta LEFT JOIN $wpdb->posts posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL");

        // Deregister CPT
        if (function_exists('unregister_post_type')) {
            unregister_post_type('swift_jobs');
            unregister_post_type('swift_staffs');
        }
    }

}

/**
 *      Enqueue scripts and styles.
 */
add_action('wp_enqueue_scripts', 'swift_staff_enqueue_scripts_styles');
if (!function_exists('swift_staff_enqueue_scripts_styles')) {

    function swift_staff_enqueue_scripts_styles() {
        wp_enqueue_style('swift-staff-custom', plugins_url('public/css/swift-staff-style.css', __FILE__), '', '', '');
        wp_enqueue_style('swiftcloud-fontawesome', plugins_url('public/css/font-awesome.min.css', __FILE__), '', '', '');
    }

}

/**
 *         Add sidebar
 */
add_action('widgets_init', 'swift_staff_widget_init');
if (!function_exists('swift_staff_widget_init')) {

    function swift_staff_widget_init() {
        register_sidebar(array(
            'name' => __('SwiftStaff Sidebar', 'impress-pr'),
            'id' => 'swift-staff-sidebar',
            'description' => __('Add widgets here to appear in swift staff sidebar', 'swift_staff'),
            'before_widget' => '<div class="swift-staff-widget-inner">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="swift-staff-widget-title">',
            'after_title' => '</h3>',
        ));
    }

}

include 'public/section/swift-staff-shortcode.php';
include 'public/section/swift-staff-function.php';

// Add jobs custom post type to feed
function jobsfeed_request($qv) {
    if (isset($qv['feed']) && !isset($qv['post_type']))
        $qv['post_type'] = array('post', 'swift_reviews', 'press_release', 'event_marketing', 'swift_jobs', 'vhcard');
    return $qv;
}

add_filter('request', 'jobsfeed_request');
