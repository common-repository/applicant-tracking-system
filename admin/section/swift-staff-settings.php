<?php
/**
 *      Setting page
 */
add_action("init", "swift_staff_settings_post_init");

function swift_staff_settings_post_init() {
    // General setting tab **************************
    if (isset($_POST['save_swift_staff_settings']) && wp_verify_nonce($_POST['save_swift_staff_settings'], 'save_swift_staff_settings')) {
        $mode_flag = sanitize_text_field(!empty($_POST['swift_staff_settings_mode_flag']) ? '1' : '0');
        $company_name = sanitize_text_field($_POST['swift_staff_settings_company_name']);
        $company_logo_url = sanitize_text_field($_POST['swift_staff_settings_company_logo']);
        $recruiter = sanitize_text_field($_POST['swift_staff_settings_recruiter']);
        $swiftstaff_careers_page_id = sanitize_text_field($_POST['swiftstaff_careers_page_id']);
        $swiftstaff_list_page_id = sanitize_text_field($_POST['swiftstaff_list_page_id']);
        $job_form_captcha_flag = sanitize_text_field($_POST['job_form_captcha_flag']);

        $update1 = update_option('swift_staff_settings_mode_flag', $mode_flag);
        $update2 = update_option('swift_staff_settings_company_name', $company_name);
        $update3 = update_option('swift_staff_settings_company_logo', $company_logo_url);
        $update4 = update_option('swift_staff_settings_recruiter', $recruiter);
        $update5 = update_option('swiftstaff_careers_page_id', $swiftstaff_careers_page_id);
        $update6 = update_option('swiftstaff_list_page_id', $swiftstaff_list_page_id);
        $update7 = update_option('job_form_captcha_flag', $job_form_captcha_flag);

        if ($update1 || $update2 || $update3 || $update4 || $update5 || $update6 || $update7) {
            wp_redirect(admin_url("admin.php?page=swift-staff&tab=staff-general-settings-tab&update=1"));
            die;
        }
    }

    // job Post tab **************************
    if (isset($_POST['save_swift_staff_job_post']) && wp_verify_nonce($_POST['save_swift_staff_job_post'], 'save_swift_staff_job_post')) {
        $swift_staff_auto_append_job_application_form = sanitize_text_field(!empty($_POST['swift_staff_auto_append_job_application_form']) ? '1' : '0');
        $swift_staff_job_application_form_id = sanitize_text_field($_POST['swift_staff_job_application_form_id']);
        $swift_staff_pre_interview_question_flag = sanitize_text_field($_POST['swift_staff_pre_interview_question_flag']);
        $swift_staff_pre_interview_questions = array_filter($_POST['swift_staff_pre_interview_questions']);

        $update1 = update_option('swift_staff_auto_append_job_application_form', $swift_staff_auto_append_job_application_form);
        $update2 = update_option('swift_staff_job_application_form_id', $swift_staff_job_application_form_id);
        $update3 = update_option('swift_staff_pre_interview_question_flag', $swift_staff_pre_interview_question_flag);
        $update4 = update_option('swift_staff_pre_interview_questions', $swift_staff_pre_interview_questions);

        if ($update1 || $update2 || $update3 || $update4) {
            wp_redirect(admin_url("admin.php?page=swift-staff&tab=staff-job-posts-tab&update=1"));
            die;
        }
    }

    // Staff custom post type tab **************************
    if (isset($_POST['save_swift_staff_cpt']) && wp_verify_nonce($_POST['save_swift_staff_cpt'], 'save_swift_staff_cpt')) {
        $swift_staff_cpt = sanitize_text_field(!empty($_POST['swift_staff_cpt']) ? '1' : '0');
        $update1 = update_option('swift_staff_cpt', $swift_staff_cpt);
        if ($update1) {
            wp_redirect(admin_url("admin.php?page=swift-staff&tab=staff-cpt&update=1"));
            die;
        }
    }

    // SEO settings tab **************************
    if (isset($_POST['save_swift_staff_seo_settings']) && wp_verify_nonce($_POST['save_swift_staff_seo_settings'], 'save_swift_staff_seo_settings')) {
        $job_slug = sanitize_text_field($_POST['swift_staff_seo_settings_job_slug']);
        $staff_slug = sanitize_text_field($_POST['swift_staff_seo_settings_staff_slug']);

        $update1 = update_option('swift_staff_seo_settings_job_slug', $job_slug);
        $update2 = update_option('swift_staff_seo_settings_staff_slug', $staff_slug);

        if ($update1 || $update2) {
            wp_redirect(admin_url("admin.php?page=swift-staff&tab=staff-seo-settings&update=1"));
            die;
        }
    }
}

if (!function_exists('swift_staff_settings_callback')) {

    function swift_staff_settings_callback() {
        ?>
        <div class="wrap">
            <h2>SwiftStaff Settings</h2><hr/>
            <?php
            if (isset($_GET['update']) && !empty($_GET['update'])) {
                if ($_GET['update'] == 1) {
                    ?>
                    <div id="message" class="notice notice-success is-dismissible below-h2">
                        <p>Settings updated successfully.</p>
                    </div>
                    <?php
                } else if ($_GET['update'] == 2) {
                    ?>
                    <div id="message" class="notice notice-success is-dismissible below-h2">
                        <p>Influencers / Media contact added successfully.</p>
                    </div>
                    <?php
                } else if ($_GET['update'] == 3) {
                    ?>
                    <div id="message" class="notice notice-success is-dismissible below-h2">
                        <p>Influencers / Media contact deleted successfully.</p>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="inner_content">
                <h2 class="nav-tab-wrapper" id="staff-setting-tabs">
                    <a class="nav-tab custom-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "staff-general-settings-tab") ? 'nav-tab-active' : ''; ?>" id="staff-general-settings-tab" href="#staff-general-settings-tab">General Settings</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-job-posts-tab") ? 'nav-tab-active' : ''; ?>" id="staff-job-posts-tab" href="#staff-job-posts-tab">Job Posts</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-cpt") ? 'nav-tab-active' : ''; ?>" id="staff-cpt" href="#staff-cpt">Staff Custom Post Type</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-seo-settings") ? 'nav-tab-active' : ''; ?>" id="staff-seo-settings" href="#staff-seo-settings">SEO</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-help-setup-settings") ? 'nav-tab-active' : ''; ?>" id="staff-help-setup-tab" href="#staff-help-setup-settings">Help & Setup</a>
                </h2>

                <div class="tabwrapper">
                    <div id="staff-general-settings-tab" class="panel <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "staff-general-settings-tab") ? 'active' : ''; ?>">
                        <?php include 'swift-staff-general-settings.php'; ?>
                    </div>
                    <div id="staff-job-posts-tab" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-job-posts-tab") ? 'active' : ''; ?>">
                        <?php include 'swift-staff-job-post.php'; ?>
                    </div>
                    <div id="staff-cpt" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-cpt") ? 'active' : ''; ?>">
                        <?php include 'swift-staff-cpt.php'; ?>
                    </div>
                    <div id="staff-seo-settings" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-seo-settings") ? 'active' : ''; ?>">
                        <?php include 'staff-seo-settings.php'; ?>
                    </div>
                    <div id="staff-help-setup-settings" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "staff-help-setup-settings") ? 'active' : ''; ?>">
                        <?php include 'swift-staff-help-page.php'; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
?>