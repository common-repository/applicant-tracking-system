<?php
/**
 *      SEO settings
 */
$swift_staff_seo_settings_job_slug = get_option('swift_staff_seo_settings_job_slug');
$swift_staff_seo_settings_staff_slug = get_option('swift_staff_seo_settings_staff_slug');
?>
<div class="wrap">
    <h2 class="sc-page-title">SEO Settings</h2>
    <hr/>
    <div class="inner_content">
        <form name="FrmSwiftStaffSEOSettings" id="FrmSwiftStaffSEOSettings" method="post">
            <table class="form-table">
                <tr>
                    <th><label for="swift_staff_seo_settings_job_slug">SEO Slug for Jobs</label></th>
                    <td><?php echo home_url('/'); ?><input type="text" id="swift_staff_seo_settings_job_slug" name="swift_staff_seo_settings_job_slug" value="<?php echo $swift_staff_seo_settings_job_slug; ?>" placeholder="job" />/job-title-goes-here</td>
                </tr>
                <tr>
                    <th><label for="swift_staff_seo_settings_staff_slug">SEO Slug for Staff</label></th>
                    <td><?php echo home_url('/'); ?><input type="text" id="swift_staff_seo_settings_staff_slug" name="swift_staff_seo_settings_staff_slug" value="<?php echo $swift_staff_seo_settings_staff_slug; ?>" placeholder="staff" />/worker-name-here</td>
                </tr>
                <tr>
                    <th colspan="2">
                        <?php wp_nonce_field('save_swift_staff_seo_settings', 'save_swift_staff_seo_settings'); ?>
                        <button type="submit" class="button-primary" id="save_swift_staff_seo_settings_btn" value="swift_staff_seo_settings" name="btn_swift_staff_seo_settings">Save Settings</button>
                    </th>
                </tr>
            </table>
        </form>
    </div>
</div>