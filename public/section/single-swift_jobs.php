<?php
/**
 * The template for displaying all single swift jobs
 *
 */
get_header();
wp_enqueue_script('swift-form-jstz', SWIFTSTAFF_PLUGIN_URL . "public/js/jstz.min.js", '', '', true);
wp_enqueue_script('swiftcloud-tooltip-min', SWIFTSTAFF_PLUGIN_URL . 'public/js/tooltipster.js', array('jquery'), '', true);
wp_enqueue_style('swiftcloud-plugin-tooltip', SWIFTSTAFF_PLUGIN_URL . 'public/css/swiftcloud-tooltip.css', '', '', '');
wp_enqueue_script('swift-staff-custom-script', SWIFTSTAFF_PLUGIN_URL . 'public/js/swift-staff-custom-script.js', array('jquery'), '', true);
wp_localize_script('swift-staff-custom-script', 'swiftstaff_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'home_url' => home_url(), 'plugin_url' => SWIFTSTAFF_PLUGIN_URL));

$swift_staff_auto_append_job_application_form = get_option('swift_staff_auto_append_job_application_form');
$swift_staff_job_application_form_id = get_option('swift_staff_job_application_form_id');
$swift_staff_pre_interview_question_flag = get_option('swift_staff_pre_interview_question_flag');
$swift_staff_pre_interview_questions = get_option('swift_staff_pre_interview_questions');
$swiftstaff_job_feed_page_id = get_option('swiftstaff_job_feed_page_id');
$swiftstaff_careers_page_id = get_option('swiftstaff_careers_page_id');
$job_form_captcha_flag = get_option('job_form_captcha_flag');

$job_captcha_arr = array("sc1.jpg", "sc2.jpg", "sc3.jpg", "sc4.jpg");
$rand_keys = array_rand($job_captcha_arr, 1);

$testing_formid = (isset($_GET['testingformID']) && !empty($_GET['testingformID']) && ($_GET['testingformID'] == "ON" || $_GET['testingformID'] == 1)) ? 659 : '';
$swift_form_id = !empty($testing_formid) ? $testing_formid : $swift_staff_job_application_form_id;
?>
<div class="swift-staff-container container">
    <div class="swift-staff-page">
        <div class="swiftstaff-row">
            <div class="swift-staff-col-8">
                <?php while (have_posts()) : the_post(); ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class('swift-staff-content-wrap'); ?>>
                        <div itemscope itemtype="http://schema.org/JobPosting">
                            <div class="swift-staff-page-title"><h1 itemprop="title"><?php the_title(); ?></h1></div>

                            <?php
                            $job_location_array = array();
                            $job_locations = wp_get_post_terms(get_the_ID(), 'swiftstaff_location_tag');

                            function getParentLocation($loc_id) {
                                $parent_loc = array();
                                $parent_loc = get_term($loc_id, 'swiftstaff_location_tag');
                                if (!empty($parent_loc)) {
                                    if ($parent_loc->parent == 0) {
                                        return $parent_loc->name;
                                    } else {
                                        getParentLocation($parent_loc->parent);
                                    }
                                }
                            }

                            if (!empty($job_locations)) {
                                foreach ($job_locations as $job_loc) {
                                    if ($job_loc->parent == 0) {
                                        
                                    } else {
                                        $job_location_array[] = $job_loc->name;
                                        $job_location_array[] = getParentLocation($job_loc->parent);
                                    }
                                }
                            }
                            $job_location = array_filter($job_location_array);
                            if (!empty($job_location)) {
                                echo '<div class="swift-staff-job-location" itemprop="jobLocation">Location: ' . @implode(", ", $job_location) . '</div>';
                            }

                            $job_tags = get_the_term_list(get_the_ID(), 'swift_jobs_tag', '', ',', '');
                            ?>
                            <div class="swift-staff-page-content" itemprop="description"><?php the_content(); ?></div>
                            <div class="swift-staff-job-posted" itemprop="datePosted"><?php the_date(); ?></div>
                            <div style="display: none" itemprop="hiringOrganization"><?php bloginfo('name'); ?></div>
                            <div style="display: none" itemprop="jobLocationType">TELECOMMUTE</div>
                            <div class="">
                                <div class="swiftstaff-tags-wrap" itemprop="occupationalCategory">
                                    <?php echo get_the_term_list(get_the_ID(), 'swift_jobs_tag', '<ul class="swiftstaff-tags-list"><li>', '</li><li>', '</li></ul>'); ?>
                                </div>
                                <div itemprop="jobLocation" style="display: none">
                                    <?php echo get_the_term_list(get_the_ID(), 'swift_jobs_category', '', ', ', ''); ?>
                                </div>
                            </div>
                            <div class="">
                                <?php if ($swift_staff_auto_append_job_application_form == 1): ?>
                                    <?php if (!empty($swift_form_id)): ?>
                                        <div class="swiftstaff_applybox">
                                            <button type="button" id='apply_for_this_position'><i class="fa fa-user-plus"></i> Apply for this position</button>
                                            <div class="swiftstaff_apply_form">
                                                <form class="ss-swift-form v-<?php echo str_replace('.', '-', SWIFTSTAFF_VERSION); ?>" name="FrmswiftStaffApply" id="FrmswiftStaffApply" action="" method="post">
                                                    <input id="ip_address" type="hidden" name="ip_address" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                                    <input type="hidden" name="browser" id="SC_browser" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                                    <input type="hidden" name="trackingvars" class="trackingvars" id="trackingvars" >
                                                    <input type="hidden" id="SC_fh_timezone" name="timezone" value="" />
                                                    <input type="hidden" id="SC_fh_language" name="language" value="" />
                                                    <input type="hidden" id="SC_fh_capturepage" name="capturepage" value="" />
                                                    <input type="hidden" id="sc_lead_referer" name="sc_lead_referer" value="" />
                                                    <input type="hidden" id="browserResolution" name="browserResolution" value="" />
                                                    <input type="hidden" name="formid" id="formid" value="<?php echo $swift_form_id; ?>" />
                                                    <input type="hidden" value="817" name="iSubscriber" />
                                                    <input id="sc_referer_qstring" type="hidden" value="" name="sc_referer_qstring" />
                                                    <input type="hidden" name="extra_job_id" value="<?php echo get_post_meta(get_the_ID(), 'swift_staff_job_id', true); ?>" />
                                                    <input type="hidden" name="extra_job_title" value="<?php echo get_the_title(); ?>" />
                                                    <input type="hidden" name="extra_job_url" value="<?php echo get_permalink(get_the_ID()); ?>" />
                                                    <input type="hidden" name="vTags" value="<?php echo strip_tags($job_tags); ?>" />

                                                    <div class="swiftstaff_form_row">
                                                        <label for='swiftstaff_applicant_name'>Name: </label>
                                                        <input type="text" name="name" id='swiftstaff_applicant_name' required="required" />
                                                    </div>
                                                    <div class="swiftstaff_form_row">
                                                        <label for='swiftstaff_applicant_email_offdomain'>Email: </label>
                                                        <input type="email" name="email_offdomain" id='swiftstaff_applicant_email_offdomain' required="required" />
                                                        <input name="email" id="swiftstaff_applicant_email" type="email" style="display: none;">
                                                    </div>
                                                    <div class="swiftstaff_form_row">
                                                        <label for='swiftstaff_applicant_phone'>Phone: </label>
                                                        <input type="text" name="phone" id='swiftstaff_applicant_phone' required="required" />
                                                    </div>
                                                    <div class="swiftstaff_form_row">
                                                        <label for='extra_swiftstaff_applicant_resume' style="vertical-align: top">Please Paste Your Resume:</label>
                                                        <?php
                                                        $settings = array('media_buttons' => false, 'teeny' => true, 'editor_height' => 300);
                                                        wp_editor('', 'swiftstaff_applicant_resume', $settings);
                                                        ?>
                                                    </div>

                                                    <?php if ($swift_staff_pre_interview_question_flag == 1): ?>
                                                        <div class="swiftstaff_form_row preInterview">
                                                            <h3>Help us understand why you stand out!</h3>
                                                        </div>

                                                        <?php foreach ($swift_staff_pre_interview_questions as $preQueKey => $preQue): ?>
                                                            <div class="swiftstaff_form_row preInterview">
                                                                <label for="extra_swiftstaff_pre_interview_question_<?php echo $preQueKey + 1; ?>"><?php echo stripslashes($preQue); ?></label>
                                                                <textarea name="extra_swiftstaff_pre_interview_question_<?php echo $preQueKey + 1; ?>" id="extra_swiftstaff_pre_interview_question_<?php echo $preQueKey + 1; ?>"></textarea>
                                                            </div>
                                                        <?php endforeach; ?>

                                                    <?php endif; ?>

                                                    <?php if (isset($job_form_captcha_flag) && !empty($job_form_captcha_flag) && $job_form_captcha_flag == 1): ?>
                                                        <div class="swiftstaff_form_row" id="job_captcha_code-container">
                                                            <label for="job_captcha_code">Please enter code below &nbsp;</label>
                                                            <div class="job_captcha_img">
                                                                <img src="<?php echo SWIFTSTAFF_PLUGIN_URL . 'public/images/' . $job_captcha_arr[$rand_keys]; ?>" alt="captcha" />
                                                            </div>
                                                            <div class="job_captcha_field">
                                                                <input type="text" name="job_captcha_code" id="job_captcha_code" />
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="swiftstaff_form_row">
                                                        <button type="button" class="swiftstaff_form_apply_now" <?php echo ($swift_staff_pre_interview_question_flag == 1) ? "data-preque='1'" : ""; ?>><i class="fa fa-send"></i> &nbsp;Apply Now</button>
                                                    </div>
                                                </form>
                                                <input type="hidden" value="<?php echo (isset($job_form_captcha_flag) && !empty($job_form_captcha_flag) && $job_form_captcha_flag == 1) ? 1 : 0; ?>" id="job_form_captcha_flag" name="job_form_captcha_flag"/>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p style='color:red;font-size:18px;'>Heads up! Your form will not display until you add a form ID number in the <a href="<?php echo admin_url() . 'admin.php?page=swift-staff&tab=staff-job-posts-tab'; ?>">control panel</a>.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="plugin-credit swiftcloud_credit">Powered by
                        <a href="https://SwiftCRM.com/" target="_blank">SwiftCloud</a>&nbsp;
                        <a href="https://wordpress.org/plugins/applicant-tracking-system/" target="_blank">Applicant Tracking System</a>
                    </div>
                    <div class="swiftcloud-edit-link"><?php edit_post_link(__('Edit', 'swift_staff'), '<span class="edit-link">', '</span>', get_the_ID()); ?></div>
                <?php endwhile; ?>
            </div>
            <div class="swift-staff-col-4 swift-staff-sidebar-bg">
                <div class="jobs-sidebar">
                    <div class="jobs-widget">
                        <div class="ss-widget-left-box">
                            <?php if ($swiftstaff_careers_page_id): ?><div class="jobs-list-link jobs-pagination-link tooltip-right" data-tooltip="Back to Job list"><a href="<?php echo get_permalink($swiftstaff_careers_page_id); ?>"><i class="fa fa-list"></i></a></div><?php endif; ?>
                        </div>
                        <div class="ss-widget-right-box">
                            <div class="jobs-prev-link jobs-pagination-link tooltip-left" data-tooltip="Send this job to someone"><a href="mailto:?Subject=<?php echo get_the_title(); ?>&Body=<?php echo get_the_permalink(); ?>" target="_new"><i class="fa fa-paper-plane"></i></a></div>
                            <div class="jobs-next-link jobs-pagination-link tooltip-left" data-tooltip="RSS Feed"><a href="<?php echo get_permalink($swiftstaff_job_feed_page_id); ?>" target="_blank"><i class="fa fa-rss fa-lg"></i></a></div>
    <!--                            <div class="jobs-next-link jobs-pagination-link tooltip-left" data-tooltip="PDF / Print Version"><a href="<?php echo get_permalink() . "?print=1&pr="; ?>"><i class="fa fa-file-pdf-o fa-lg"></i></a></div>-->
                        </div>
                    </div>
                    <?php include_once 'swift-staff-sidebar.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>