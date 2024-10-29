<?php
/*
 *      CPT: Press Release
 */

add_action('init', 'swift_staff_cpt_swift_jobs_callback', 9);
if (!function_exists('swift_staff_cpt_swift_jobs_callback')) {

    function swift_staff_cpt_swift_jobs_callback() {
        $icon_url = plugins_url('../images/swiftcloud.png', __FILE__);
        $labels = array(
            'name' => _x('Jobs', 'post type general name', 'swift_staff'),
            'singular_name' => _x('Job', 'post type singular name', 'swift_staff'),
            'menu_name' => _x('Jobs', 'admin menu', 'swift_staff'),
            'add_new' => _x('Add New', '', 'swift_staff'),
            'add_new_item' => __('Add New', 'swift_staff'),
            'new_item' => __('New Job', 'swift_staff'),
            'edit_item' => __('Edit Job', 'swift_staff'),
            'view_item' => __('View Jobs', 'swift_staff'),
            'all_items' => __('All Jobs', 'swift_staff'),
            'search_items' => __('Search Job', 'swift_staff'),
            'not_found' => __('No jobs found....yet.', 'swift_staff'),
            'not_found_in_trash' => __('No job found in trash.', 'swift_staff')
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => __($icon_url, 'swift_staff'),
            'menu_position' => null,
            'supports' => array('title', 'editor'),
            'taxonomies' => array('swift_jobs_category'),
            'rewrite' => array('slug' => 'jobs')
        );
        register_post_type('swift_jobs', $args);

        /* -------------------------------------
         *      Add new taxonomy
         */
        $cat_labels = array(
            'name' => _x('Jobs Categories', 'taxonomy general name'),
            'singular_name' => _x('Jobs Category', 'taxonomy singular name'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'menu_name' => __('Categories'),
            'search_items' => __('Search Category'),
            'not_found' => __('No Category found.'),
        );

        $cat_args = array(
            'hierarchical' => true,
            'labels' => $cat_labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'swift_jobs_category'),
        );

        register_taxonomy('swift_jobs_category', 'swift_jobs', $cat_args);

        // insert default tags
        $default_cat = array(
            "Position Type" => array(
                "child" => array(
                    'Full Time',
                    'Part Time'
                )
            ),
            "Hire Type" => array(
                "child" => array(
                    'Permanent',
                    'temporary',
                    'Temp-to-Perm',
                    'Freelance'
                )
            ),
            "Qualifications" => array(
                "child" => array(
                    'Degree' => array(
                        "subchild" => array(
                            'PhD',
                            'Master',
                            'Bachelors',
                            'Associate',
                            'High School Diploma or GED'
                        ),
                    ),
                    'License' => array(
                        "subchild" => array(
                            'Series 63'
                        )
                    ),
                    'Experience' => array(
                        "subchild" => array(
                            'Non / Entry Level',
                            '1+ Years',
                            '5+ Years'
                        ),
                    )
                )
            ),
            "Location Type" => array(
                "child" => array(
                    'On-Site No / Rare Travel',
                    'Off Site / Remote',
                    'On-Site, Travel Required'
                )
            ),
            "Location" => array(
                "child" => array(
                    'Los Angeles'
                )
            ),
            "Recruiter" => array(
                "child" => array(
                    'Jane Doe'
                )
            ),
            "Industry" => array(
                "child" => array(
                    'Automotive'
                )
            ),
        );
        foreach ($default_cat as $d_cat_key => $d_cat_val) {
            // insert parent category
            if (isset($d_cat_val['child'])) {
                $parent_cat = $d_cat_key;
            } else {
                $parent_cat = $d_cat_val;
            }
            $term_id = wp_insert_term($parent_cat, "swift_jobs_category", array('parent' => 0));

            if (!is_wp_error($term_id) && !empty($term_id['term_id']) && isset($d_cat_val['child']) && !empty($d_cat_val['child'])) {
                foreach ($d_cat_val['child'] as $child_key => $child_val) {
                    // insert child category
                    if (isset($child_val['subchild'])) {
                        $child_cat = $child_key;
                    } else {
                        $child_cat = $child_val;
                    }
                    $child_term_id = wp_insert_term($child_cat, "swift_jobs_category", array('parent' => $term_id['term_id']));


                    if (!is_wp_error($child_term_id) && !empty($child_term_id['term_id']) && isset($child_val['subchild']) && !empty($child_val['subchild'])) {
                        foreach ($child_val['subchild'] as $subchild) {
                            // insert subchild category
                            $subchild_term_id = wp_insert_term($subchild, "swift_jobs_category", array('parent' => $child_term_id['term_id']));
                        }
                    }
                }
            }
        }//foreach

        /**
         *      Jobs tags
         */
        $swiftstaff_tags_labels = array(
            'name' => _x('Jobs Tags', 'taxonomy general name'),
            'singular_name' => _x('Jobs Tag', 'taxonomy singular name'),
            'search_items' => __('Search Tags'),
            'popular_items' => __('Popular Tags'),
            'all_items' => __('All Tags'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Tag'),
            'update_item' => __('Update Tag'),
            'add_new_item' => __('Add New Tag'),
            'new_item_name' => __('New Tag Name'),
            'separate_items_with_commas' => __('Separate tags with commas'),
            'add_or_remove_items' => __('Add or remove tags'),
            'choose_from_most_used' => __('Choose from the most used tags'),
            'menu_name' => __('Tags'),
        );

        register_taxonomy('swift_jobs_tag', 'swift_jobs', array(
            'hierarchical' => false,
            'labels' => $swiftstaff_tags_labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'swift_jobs_tag'),
        ));

        /**
         *      Location tags
         */
        $swiftstaff_locations_labels = array(
            'name' => _x('Jobs Locations', 'taxonomy general name'),
            'singular_name' => _x('Jobs Location', 'taxonomy singular name'),
            'search_items' => __('Search Locations'),
            'popular_items' => __('Popular Locations'),
            'all_items' => __('All Locations'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Tag'),
            'update_item' => __('Update Tag'),
            'add_new_item' => __('Add New Tag'),
            'new_item_name' => __('New Tag Name'),
            'separate_items_with_commas' => __('Separate locations with commas'),
            'add_or_remove_items' => __('Add or remove locations'),
            'choose_from_most_used' => __('Choose from the most used locations'),
            'menu_name' => __('Locations'),
        );

        register_taxonomy('swiftstaff_location_tag', 'swift_jobs', array(
            'hierarchical' => true,
            'labels' => $swiftstaff_locations_labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'swiftstaff_location_tag'),
        ));

        // register post type for staff plugin
        $staff_labels = array(
            'name' => _x('Staff List', 'post type general name', 'swift_staff'),
            'singular_name' => _x('Staff', 'post type singular name', 'swift_staff'),
            'menu_name' => _x('Staff', 'admin menu', 'swift_staff'),
            'add_new' => _x('Add New', '', 'swift_staff'),
            'add_new_item' => __('Add New', 'swift_staff'),
            'new_item' => __('New Staff', 'swift_staff'),
            'edit_item' => __('Edit Staff', 'swift_staff'),
            'view_item' => __('Staff List', 'swift_staff'),
            'all_items' => __('Staff List', 'swift_staff'),
            'search_items' => __('Search Staff', 'swift_staff'),
            'not_found' => __('No staff found....yet.', 'swift_staff'),
            'not_found_in_trash' => __('No staff found in trash.', 'swift_staff')
        );
        $staff_args = array(
            'labels' => $staff_labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => __($icon_url, 'swift_staff'),
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array(),
            'rewrite' => array('slug' => 'staff')
        );
        register_post_type('swift_staffs', $staff_args);

        /**
         *      Staff tags
         */
        $swiftstaff_tags_labels = array(
            'name' => _x('Staff Tags', 'taxonomy general name'),
            'singular_name' => _x('Staff Tag', 'taxonomy singular name'),
            'search_items' => __('Search Tags'),
            'popular_items' => __('Popular Tags'),
            'all_items' => __('All Tags'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Tag'),
            'update_item' => __('Update Tag'),
            'add_new_item' => __('Add New Tag'),
            'new_item_name' => __('New Tag Name'),
            'separate_items_with_commas' => __('Separate tags with commas'),
            'add_or_remove_items' => __('Add or remove tags'),
            'choose_from_most_used' => __('Choose from the most used tags'),
            'menu_name' => __('Tags'),
        );

        register_taxonomy('swift_staffs_tag', 'swift_staffs', array(
            'hierarchical' => false,
            'labels' => $swiftstaff_tags_labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'swift_staff_tag'),
        ));

        // flush rewrite rules
        flush_rewrite_rules();
    }

}

/* change permalinks for swift job & staff from settings */

function change_swift_jobs_post_types_slug($args, $post_type) {
    $swift_staff_seo_settings_job_slug = get_option('swift_staff_seo_settings_job_slug');
    $swift_staff_seo_settings_staff_slug = get_option('swift_staff_seo_settings_staff_slug');
    if ('swift_jobs' === $post_type && !empty($swift_staff_seo_settings_job_slug)) {
        $args['rewrite']['slug'] = $swift_staff_seo_settings_job_slug;
    }
    return $args;
}

add_filter('register_post_type_args', 'change_swift_jobs_post_types_slug', 10, 2);

function change_swift_staffs_post_types_slug($args, $post_type) {
    $swift_staff_seo_settings_staff_slug = get_option('swift_staff_seo_settings_staff_slug');
    if ('swift_staffs' === $post_type && !empty($swift_staff_seo_settings_staff_slug)) {
        $args['rewrite']['slug'] = $swift_staff_seo_settings_staff_slug;
    }
    return $args;
}

add_filter('register_post_type_args', 'change_swift_staffs_post_types_slug', 10, 2);

/*
 *  Custom field : swift_jobs cpt
 */

add_action('add_meta_boxes', 'swift_staff_metaboxes');
if (!function_exists('swift_staff_metaboxes')) {

    function swift_staff_metaboxes() {
        add_meta_box('swift_staff_job_id', 'Job ID#', 'swift_staff_job_id_callback', 'swift_jobs', 'normal', 'default');
        add_meta_box('swift_staff_job_title', 'Job Title', 'swift_staff_job_title_callback', 'swift_staffs', 'normal', 'default');
        add_meta_box('swift_staff_pay_rate', 'Pay Rate', 'swift_staff_pay_rate_callback', 'swift_jobs', 'normal', 'default');
        add_meta_box('swift_staff_pay_type', 'Pay Type', 'swift_staff_pay_type_callback', 'swift_jobs', 'normal', 'default');
        add_meta_box('swift_staff_swiftcloud_username', 'SwiftCloud Username', 'swift_staff_swiftcloud_username_callback', 'swift_staffs', 'normal', 'default');
    }

}

// Job ID metabox
if (!function_exists('swift_staff_job_id_callback')) {

    function swift_staff_job_id_callback($post) {
        $job_id = get_post_meta($post->ID, 'swift_staff_job_id', true);
        ?>
        <input type="text" name="swift_staff_job_id" id="swift_staff_job_id" class="regular-text" value="<?php echo $job_id; ?>" />
        <?php
    }

}

// Job title meta box
if (!function_exists('swift_staff_job_title_callback')) {

    function swift_staff_job_title_callback($post) {
        $job_title = get_post_meta($post->ID, 'swift_staff_job_title', true);
        ?>
        <input type="text" name="swift_staff_job_title" id="swift_staff_job_title" class="regular-text" value="<?php echo $job_title; ?>" />
        <?php
    }

}

// job pay rate meta box
if (!function_exists('swift_staff_pay_rate_callback')) {

    function swift_staff_pay_rate_callback($post) {
        $pay_rate = get_post_meta($post->ID, 'swift_staff_pay_rate', true);
        ?>
        <input type="text" name="swift_staff_pay_rate" id="swift_staff_pay_rate" class="regular-text" value="<?php echo $pay_rate; ?>" />
        <?php
    }

}

// job pay type meta box
if (!function_exists('swift_staff_pay_type_callback')) {

    function swift_staff_pay_type_callback($post) {
        $pay_type = get_post_meta($post->ID, 'swift_staff_pay_type', true);
        ?>
        <select name="swift_staff_pay_type" id="swift_staff_pay_type">
            <option value="year">/year</option>
            <option value="month">/month</option>
            <option value="2x/month">/2x/month</option>
            <option value="bimonthly">/bimonthly</option>
            <option value="weekly">/weekly</option>
            <option value="hourly">/hourly</option>
            <option value="other">/other</option>
        </select>
        <?php
    }

}


// url to swiftcloud profile meta box
if (!function_exists('swift_staff_swiftcloud_username_callback')) {

    function swift_staff_swiftcloud_username_callback($post) {
        $swift_staff_swiftcloud_username = get_post_meta($post->ID, 'swift_staff_swiftcloud_username', true);
        ?>
        <input type="text" name="swift_staff_swiftcloud_username" id="swift_staff_swiftcloud_username" class="regular-text" value="<?php echo $swift_staff_swiftcloud_username; ?>" />
        <?php
    }

}


/**
 *      Save meta
 */
add_action('save_post', 'swift_staff_save_custom_fields');
if (!function_exists('swift_staff_save_custom_fields')) {

    function swift_staff_save_custom_fields($post_id) {
        if (isset($_POST["swift_staff_job_id"])) {
            $swift_staff_job_id = sanitize_text_field($_POST['swift_staff_job_id']);
            update_post_meta($post_id, 'swift_staff_job_id', $swift_staff_job_id);
        }
        if (isset($_POST["swift_staff_job_title"])) {
            $swift_staff_job_title = sanitize_text_field($_POST['swift_staff_job_title']);
            update_post_meta($post_id, 'swift_staff_job_title', $swift_staff_job_title);
        }
        if (isset($_POST["swift_staff_pay_rate"])) {
            $swift_staff_pay_rate = sanitize_text_field($_POST['swift_staff_pay_rate']);
            update_post_meta($post_id, 'swift_staff_pay_rate', $swift_staff_pay_rate);
        }
        if (isset($_POST["swift_staff_pay_type"])) {
            $swift_staff_pay_type = sanitize_text_field($_POST['swift_staff_pay_type']);
            update_post_meta($post_id, 'swift_staff_pay_type', $swift_staff_pay_type);
        }
        if (isset($_POST["swift_staff_swiftcloud_username"])) {
            $swift_staff_swiftcloud_username = sanitize_text_field($_POST['swift_staff_swiftcloud_username']);
            update_post_meta($post_id, 'swift_staff_swiftcloud_username', $swift_staff_swiftcloud_username);
        }
    }

}

add_filter('single_template', 'swift_staff_plugin_templates_callback');
if (!function_exists('swift_staff_plugin_templates_callback')) {

    function swift_staff_plugin_templates_callback($template) {
        $post_types = array('swift_jobs');
        if (is_singular($post_types) && !file_exists(get_stylesheet_directory() . '/single-swift_jobs.php')) {
            $template = SWIFTSTAFF_PLUGIN_DIR . "public/section/single-swift_jobs.php";
        } else if (is_singular('swift_staffs') && !file_exists(get_stylesheet_directory() . '/single-swift_staffs.php')) {
            $template = SWIFTSTAFF_PLUGIN_DIR . "public/section/single-swift_staffs.php";
        }
        return $template;
    }

}

add_filter('archive_template', 'swift_staff_set_archive_template_callback');
if (!function_exists('swift_staff_set_archive_template_callback')) {

    function swift_staff_set_archive_template_callback($archive_template) {
        global $post;
        if (get_post_type() == 'swift_jobs' && is_archive('swift_jobs')) {
            $archive_template = SWIFTSTAFF_PLUGIN_DIR . 'public/section/archive-swift_jobs.php';
        } else if (get_post_type() == 'swift_staffs' && is_archive('swift_staffs')) {
            $archive_template = SWIFTSTAFF_PLUGIN_DIR . 'public/section/archive-swift_staffs.php';
        }
        return $archive_template;
    }

}

function swiftstaff_order($query) {
    // exit out if it's the admin or it isn't the main query
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    // order category archives by title in ascending order
    if (is_post_type_archive('swift_staffs')) {
        $query->set('order', 'asc');
        $query->set('orderby', 'date');
        return;
    }
}

add_action('pre_get_posts', 'swiftstaff_order', 1);
?>