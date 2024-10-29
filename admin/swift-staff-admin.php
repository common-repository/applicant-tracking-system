<?php
/**
 *      Admin
 */
/** On plugin activation notice * */
if (version_compare($GLOBALS['wp_version'], SWIFTSTAFF_VERSION, '>=')) {
    add_action('admin_notices', 'swift_staff_admin_notice_callback');
}
if (!function_exists('swift_staff_admin_notice_callback')) {

    function swift_staff_admin_notice_callback() {
        if (!get_option('swift_staff_notice') && !get_option('swift_staff_pre_load_pages')) {
            ?>
            <div class="notice notice-success is-dismissible swift-staff-admin-notice" id="swift-staff-admin-notice">
                <p><b>SwiftStaff Plugin</b></p>
                <form method="post">
                    <p class="sc-notice-msg"><?php _e('Want to auto-create the following pages to quickly get you set up? ', 'swift-calendar'); ?></p>
                    <ul>
                        <li>Careers</li>
                        <li>Thanks for Applying</li>
                        <li>Jobs RSS Feed</li>
                        <li>Staff List</li>
                    </ul>
                    <?php wp_nonce_field('swift_staff_autogen_pages', 'swift_staff_autogen_pages'); ?>
                    <button type="submit" value="yes" name="swift_staff_autogen_yes" class="button button-green"><i class="fa fa-check"></i> Yes</button>  <button type="submit" name="swift_staff_autogen_no" value="no" class="button button-default button-red"><i class="fa fa-ban"></i> No</button>
                </form>
            </div>
            <?php
        }
    }

}

/**
 *      Admin menu
 */
add_action('admin_menu', 'swift_staff_admin_menu_callback');
if (!function_exists('swift_staff_admin_menu_callback')) {

    function swift_staff_admin_menu_callback() {
        $icon_url = plugins_url('/images/swiftcloud.png', __FILE__);
        $parent_menu_slug = 'swift-staff';
        $menu_capability = 'manage_options';

        add_menu_page('SwiftStaff', 'SwiftStaff', $menu_capability, $parent_menu_slug, 'swift_staff_settings_callback', $icon_url, null);
        add_submenu_page($parent_menu_slug, "Settings", "Settings", $menu_capability, $parent_menu_slug);
        //cpt menu
        add_submenu_page($parent_menu_slug, "All Jobs", "All Jobs", $menu_capability, "edit.php?post_type=swift_jobs", null);
        add_submenu_page($parent_menu_slug, "Add Job", "Add Job", $menu_capability, "post-new.php?post_type=swift_jobs", null);
        add_submenu_page($parent_menu_slug, "Categories", "Categories", $menu_capability, "edit-tags.php?taxonomy=swift_jobs_category&post_type=swift_jobs", null);
        add_submenu_page($parent_menu_slug, "Tags", "Tags", $menu_capability, "edit-tags.php?taxonomy=swift_jobs_tag&post_type=swift_jobs", null);
        add_submenu_page($parent_menu_slug, "Locations", "Locations", $menu_capability, "edit-tags.php?taxonomy=swiftstaff_location_tag&post_type=swift_jobs", null);

        if (get_option('swift_staff_cpt')) {
            add_submenu_page($parent_menu_slug, "Staff List", "Staff List", $menu_capability, "edit.php?post_type=swift_staffs", null);
            add_submenu_page($parent_menu_slug, "Add Staff", "Add Staff", $menu_capability, "post-new.php?post_type=swift_staffs", null);
        }
        add_submenu_page($parent_menu_slug, "Updates & Tips", "Updates & Tips", $menu_capability, "swift_staff_dashboard", 'swift_staff_dashboard_cb');
        
        $page_hook_suffix = add_submenu_page($parent_menu_slug, 'Form Submission', 'Form Submission', 'manage_options', 'swift_staff_admin_display_log', 'swift_staff_admin_display_log');
        add_submenu_page("", "Log Detail", "Log Detail", 'manage_options', 'swift_staff_admin_display_log_details', 'swift_staff_admin_display_log_details');
    }

}
/**
 *      Set current menu selected
 */
add_filter('parent_file', 'swift_staff_set_current_menu');
if (!function_exists('swift_staff_set_current_menu')) {

    function swift_staff_set_current_menu($parent_file) {
        global $submenu_file, $current_screen, $pagenow;

        if ($current_screen->post_type == 'swift_jobs') {
            if ($pagenow == 'post.php') {
                $submenu_file = "edit.php?swift_jobs=" . $current_screen->post_type;
            }
            if ($pagenow == 'edit-tags.php') {
                if ($current_screen->taxonomy == 'swift_jobs_category') {
                    $submenu_file = "edit-tags.php?taxonomy=swift_jobs_category&post_type=" . $current_screen->post_type;
                }
            }
            $parent_file = 'swift-staff';
        }
        return $parent_file;
    }

}

/**
 *      Admin enqueue script and styles
 */
add_action('admin_enqueue_scripts', 'swift_staff_admin_enqueue');
if (!function_exists('swift_staff_admin_enqueue')) {

    function swift_staff_admin_enqueue($hook) {
        wp_enqueue_style('swift-staff-admin', plugins_url('/css/swift-staff-admin-style.css', __FILE__));

        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_style('swift-cloud-jquery-ui', plugins_url('/css/jquery-ui.min.css', __FILE__));

        wp_enqueue_style('swift-toggle-style', plugins_url('/css/sc_rcswitcher.css', __FILE__), '', '', '');
        wp_enqueue_script('swift-toggle', plugins_url('/js/sc_rcswitcher.js', __FILE__), array('jquery'), '', true);

        wp_enqueue_script('swift-staff-admin-script', plugins_url('/js/swift-staff-admin-script.js', __FILE__), array('jquery'), '', true);
        wp_localize_script('swift-staff-admin-script', 'swift_staff_admin_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_style('swiftcloud-fontawesome', plugins_url('../public/css/font-awesome.min.css', __FILE__), '', '', '');

        //only for setting page : image upload
        if (isset($_GET['page']) && $_GET['page'] == 'swift-staff') {
            wp_enqueue_media();
            wp_enqueue_script('swift-staff-upload-media', plugins_url('/js/swift-staff-admin-media-upload.js', __FILE__), array('jquery'), '', true);
        }
    }

}

include_once 'section/cpt-swift-jobs.php';
include_once 'section/swift-staff-widget-latest-jobs.php';
include_once 'section/swift-staff-settings.php';
include_once 'section/swift-staff-widget-search.php';
include_once 'section/swift-staff-dashboard.php';
include_once 'section/swift-staff-local-capture.php';

/*
 *      Init
 */
add_action("init", "swift_staff_admin_forms_submit");

function swift_staff_admin_forms_submit() {
    /* on plugin active auto generate pages and options */
    if (isset($_POST['swift_staff_autogen_pages']) && wp_verify_nonce($_POST['swift_staff_autogen_pages'], 'swift_staff_autogen_pages')) {
        if ($_POST['swift_staff_autogen_yes'] == 'yes') {
            swift_staff_pre_load_data();
        }
        update_option('swift_staff_notice', true);
    }
}

/* Dismiss notice callback */
add_action('wp_ajax_swift_staff_dismiss_notice', 'swift_staff_dismiss_notice_callback');
add_action('wp_ajax_nopriv_swift_staff_dismiss_notice', 'swift_staff_dismiss_notice_callback');

function swift_staff_dismiss_notice_callback() {
    update_option('swift_staff_notice', true);
    wp_die();
}
?>