<?php

/**
 *      Pre load data
 */
if (!function_exists('swift_staff_pre_load_data')) {

    function swift_staff_pre_load_data() {

        update_option('swift_staff_auto_append_job_application_form', 1);   //default ON
        update_option('swift_staff_pre_interview_questions', array('Of what accomplishments are you most proud?', 'Promotions: Was there a job promotion you deserved, but didn\'t get? Or if you were promoted, what did you do that made you stand out?', 'What kind of oversight and interaction would your ideal boss provide?', 'If we contact your last supervisor and ask which area of your work needs the most improvement, what will we learn?', 'Where do you see yourself in five years?', 'Why should we hire you?'));

        global $wpdb;
        $table_name = $wpdb->prefix . 'swift_staff_log';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		date_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name varchar(255) DEFAULT '' NOT NULL,
		email varchar(255) DEFAULT '' NOT NULL,
		phone varchar(255) DEFAULT '' NOT NULL,
		status TINYINT DEFAULT '0' NOT NULL,
                form_data TEXT, 
		UNIQUE KEY id (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        /**
         *   Auto generate pages
         */
        $page_id = 0;
        $page_id_array = array();

        $swift_staff_careers = wp_kses_post('[swiftstaff_jobs]');
        $swift_staff_thanks_applicant = wp_kses_post('<h2>Coming soon....</h2>');
        $swift_staff_rss_feed_content = wp_kses_post('This page is being used for RSS Feed');

        $pages_array = array(
            "careers" => array("title" => sanitize_text_field("Careers"), "content" => $swift_staff_careers, "slug" => "careers", "option" => "swiftstaff_careers_page_id", "template" => "swift-job-template.php"),
            "thanks_applicant" => array("title" => sanitize_text_field("Thanks for Applying"), "content" => $swift_staff_thanks_applicant, "slug" => "thanks-applicant", "option" => "swiftstaff_thanks_applicant_page_id"),
            "job-feed" => array("title" => sanitize_text_field("Jobs Feed"), "content" => $swift_staff_rss_feed_content, "slug" => "jobs-feed", "option" => "swiftstaff_job_feed_page_id", "template" => "rss-jobs-feed.php"),
            "staff_list" => array("title" => sanitize_text_field("Staff List"), "content" => "<p>[swift_staff_list]</p>", "slug" => "staff_list", "option" => "swiftstaff_list_page_id"),
        );

        foreach ($pages_array as $key => $page) {
            $page_data = array(
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_title' => $page['title'],
                'post_name' => $page['slug'],
                'post_content' => $page['content'],
                'comment_status' => 'closed'
            );

            $page_id = wp_insert_post($page_data);
            $page_id_array[] = $page_id;

            if (isset($page['option']) && !empty($page['option'])) {
                update_option($page['option'], sanitize_text_field($page_id));
            }

            /* Set default template */
            if (isset($page['template']) && !empty($page['template'])) {
                update_post_meta($page_id, "_wp_page_template", sanitize_text_field($page['template']));
            }
        }
        $swift_staff_pages_ids = @implode(",", $page_id_array);
        if (!empty($swift_staff_pages_ids)) {
            update_option('swift_staff_pre_load_pages', sanitize_text_field($swift_staff_pages_ids));
        }
    }

}
?>
