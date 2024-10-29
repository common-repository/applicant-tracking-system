<?php
/**
 *      General setting page
 */
$swift_staff_settings_mode_flag = get_option('swift_staff_settings_mode_flag');
$swift_staff_settings_company_name = get_option('swift_staff_settings_company_name');
$swift_staff_settings_company_logo = get_option('swift_staff_settings_company_logo');
$swift_staff_settings_recruiter = get_option('swift_staff_settings_recruiter');
$swift_staff_job_form_captcha_flag = get_option('job_form_captcha_flag');
?>
<div class="wrap">
    <h2 class="sc-page-title">General Settings</h2>
    <hr/>
    <div class="inner_content">
        <form name="FrmSwiftStaffSettings" id="FrmSwiftStaffSettings" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th><label for="swift_staff_settings_mode">Mode</label></th>
                    <td>
                        <select name="swift_staff_settings_mode_flag" id="swift_staff_settings_mode_flag" class="swift_staff_settings_mode_flag">
                            <option value="0" <?php echo ($swift_staff_settings_mode_flag == 0 ? 'selected="selected"' : "") ?>>Single Employer</option>
                            <option value="1" <?php echo ($swift_staff_settings_mode_flag == 1 ? 'selected="selected"' : "") ?>>Staffing / Recruiter</option>
                        </select>
                    </td>
                </tr>
                <tr class="staff-single-mode" <?php echo ($swift_staff_settings_mode_flag == 1 ? 'style="display: none;"' : "") ?>>
                    <th><label for="swift_staff_settings_company_name">Company Name</label></th>
                    <td><input type="text" id="swift_staff_settings_company_name" name="swift_staff_settings_company_name" class="regular-text" value="<?php echo $swift_staff_settings_company_name; ?>" placeholder="Company name" /></td>
                </tr>
                <tr class="staff-recuiter-mode" <?php echo ($swift_staff_settings_mode_flag == 0 ? 'style="display: none;"' : "") ?>>
                    <th><label for="swift_staff_settings_recruiter">Staffing / Recruiter</label></th>
                    <td><input type="text" id="swift_staff_settings_recruiter" name="swift_staff_settings_recruiter" class="regular-text" value="<?php echo $swift_staff_settings_recruiter; ?>" placeholder="" /></td>
                </tr>
                <tr>
                    <th><label for="swift_staff_settings_company_logo">Company Logo</label></th>
                    <td>
                        <input id="swift_staff_upload_image" type="text" size="36" name="swift_staff_settings_company_logo" class="regular-text" value="<?php echo $swift_staff_settings_company_logo; ?>" placeholder="Company Logo URL" />
                        <input id="swift_staff_upload_image_button" class="button button-primary" type="button" value="Upload Image" />
                    </td>
                </tr>
                <tr>
                    <th><label for="swiftstaff_careers_page_id">Job List Page</label></th>
                    <td>
                        <?php
                        $args = array(
                            'name' => 'swiftstaff_careers_page_id',
                            'id' => 'swiftstaff_careers_page_id',
                            'show_option_none' => 'Select Job List Page',
                            'selected' => get_option('swiftstaff_careers_page_id')
                        );
                        wp_dropdown_pages($args);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="swiftstaff_list_page_id">Staff List Page</label></th>
                    <td>
                        <?php
                        $args = array(
                            'name' => 'swiftstaff_list_page_id',
                            'id' => 'swiftstaff_list_page_id',
                            'show_option_none' => 'Select Staff List Page',
                            'selected' => get_option('swiftstaff_list_page_id')
                        );
                        wp_dropdown_pages($args);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="">Enable Captcha</label></th>
                    <td>
                        <?php $captchaFlag = (isset($swift_staff_job_form_captcha_flag) && $swift_staff_job_form_captcha_flag == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="job_form_captcha_flag" id="job_form_captcha_flag" class="help_form_captcha_flag" <?php echo $captchaFlag; ?>>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <?php wp_nonce_field('save_swift_staff_settings', 'save_swift_staff_settings'); ?>
                        <button type="submit" class="button-primary" id="save_swift_staff_settings_btn" value="swift_staff_settings" name="btn_swift_staff_settings">Save Settings</button>
                    </th>
                </tr>
            </table>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#job_form_captcha_flag').rcSwitcher();

                    jQuery("#swift_staff_settings_mode_flag").on("change", function () {
                        if (jQuery(this).val() == 0) {
                            jQuery(".staff-single-mode").fadeIn();
                            jQuery(".staff-recuiter-mode").fadeOut();
                        } else {
                            jQuery(".staff-single-mode").fadeOut();
                            jQuery(".staff-recuiter-mode").fadeIn();
                        }
                    });
                    /* jQuery('#swift_staff_settings_mode_flag:checkbox').rcSwitcher({
                     width: 180,
                     height: 22,
                     autoFontSize: true
                     }).on({
                     'turnon.rcSwitcher': function(e, dataObj) {
                     jQuery(".staff-single-mode").fadeOut();
                     jQuery(".staff-recuiter-mode").fadeIn();
                     },
                     'turnoff.rcSwitcher': function(e, dataObj) {
                     jQuery(".staff-single-mode").fadeIn();
                     jQuery(".staff-recuiter-mode").fadeOut();

                     }
                     });*/
                });
            </script>
        </form>
    </div>
</div>