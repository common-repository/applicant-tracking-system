<?php
/**
 *      job Post page
 */
$swift_staff_auto_append_job_application_form = get_option('swift_staff_auto_append_job_application_form');
$swift_staff_job_application_form_id = get_option('swift_staff_job_application_form_id');
$swift_staff_pre_interview_question_flag = get_option('swift_staff_pre_interview_question_flag');
$swift_staff_pre_interview_questions = get_option('swift_staff_pre_interview_questions');
?>
<div class="wrap">
    <h2 class="sc-page-title">Job Post</h2>
    <hr/>
    <div class="inner_content">
        <form name="FrmSwiftStaffJobPosts" id="FrmSwiftStaffJobPosts" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th><label for="swift_staff_auto_append_job_application_form">Auto-Append Job Application Form to Job Posts:</label></th>
                    <td>
                        <?php $swift_staff_auto_append_job_application_form_toggle = ($swift_staff_auto_append_job_application_form == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_staff_auto_append_job_application_form" id="swift_staff_auto_append_job_application_form" <?php echo $swift_staff_auto_append_job_application_form_toggle; ?>>
                    </td>
                </tr>
                <tr id="swift_staff_job_application_form_id_row" <?php echo $swift_staff_auto_append_job_application_form == 1 ? 'style="display:table-row"' : 'style="display:none"'; ?>>
                    <th><label for="swift_staff_job_application_form_id"><a href="https://crm.swiftcrm.com/drive/" target="_blank">My SwiftCloud Job Application Form ID:</a></label></th>
                    <td>
                        <input id="swift_staff_job_application_form_id" type="text" name="swift_staff_job_application_form_id" class="regular-text" value="<?php echo $swift_staff_job_application_form_id; ?>" placeholder="SwiftCloud Job Application Form ID" />
                    </td>
                </tr>
                <tr>
                    <th><label for="swift_staff_pre_interview_question_flag">Pre-Interview Questions:</label></th>
                    <td>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_staff_pre_interview_question_flag" id="swift_staff_pre_interview_question_flag" <?php echo ($swift_staff_pre_interview_question_flag == 1 ? 'checked="checked"' : ""); ?>>
                    </td>
                </tr>
                <tr id="swiftstaff_pre_quetion_row" style="<?php echo ($swift_staff_pre_interview_question_flag == 1) ? 'display: table-row' : "display: none"; ?>">
                    <td colspan="2">
                        <table class="widefat fixed striped" id="swiftstaff_pre_quetion_table">
                            <thead>
                                <tr>
                                    <th width="90%"><b>Questions</b></th>
                                    <th width="10%" align="center"><b>Action</b></th>
                                </tr>
                            </thead>
                            <tbody id="pre_ques_body">
                                <?php if (isset($swift_staff_pre_interview_questions) && !empty($swift_staff_pre_interview_questions)): ?>
                                    <?php foreach ($swift_staff_pre_interview_questions as $pre_que_key => $pre_que): ?>
                                        <tr id="newrow_<?php echo $pre_que_key; ?>">
                                            <td width="90%"><input type="text" name="swift_staff_pre_interview_questions[]" class="pre_question_txt" value="<?php echo stripslashes($pre_que); ?>" /></td>
                                            <td width="10%" align="center">
                                                <img src="<?php echo plugins_url('../images/delete.png', __FILE__); ?>" class="swiftstaff_preque_min" alt="Delete" title="Delete" name="ar_row_min" />

                                                <?php if ($pre_que_key == count($swift_staff_pre_interview_questions) - 1): ?>
                                                    <img src="<?php echo plugins_url('../images/plus.png', __FILE__); ?>" class="swiftstaff_preque_add" name="ar_row_add" alt="Add New" title="Add New" />
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td width="90%"><input type="text" name="swift_staff_pre_interview_questions[]" class="pre_question_txt" /></td>
                                        <td width="10%" align="center"><img src="<?php echo plugins_url('../images/plus.png', __FILE__); ?>" class="swiftstaff_preque_add" name="ar_row_add" alt="Add New" title="Add New" /></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <?php wp_nonce_field('save_swift_staff_job_post', 'save_swift_staff_job_post'); ?>
                        <button type="submit" class="button-primary" id="save_swift_staff_job_post_btn" value="swift_staff_settings" name="btn_swift_staff_settings">Save Settings</button>
                    </th>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#swift_staff_auto_append_job_application_form:checkbox').rcSwitcher({
            autoFontSize: true
        }).on({
            'turnon.rcSwitcher': function(e, dataObj) {
                jQuery("#swift_staff_job_application_form_id_row").fadeIn();
            },
            'turnoff.rcSwitcher': function(e, dataObj) {
                jQuery("#swift_staff_job_application_form_id_row").fadeOut();

            }
        });

        jQuery('#swift_staff_pre_interview_question_flag:checkbox').rcSwitcher({
            autoFontSize: true
        }).on({
            'turnon.rcSwitcher': function(e, dataObj) {
                jQuery("#swiftstaff_pre_quetion_row").fadeIn();
            },
            'turnoff.rcSwitcher': function(e, dataObj) {
                jQuery("#swiftstaff_pre_quetion_row").fadeOut();
            }
        });

        jQuery("#FrmSwiftStaffJobPosts").submit(function(e) {
            jQuery(".emailOptError").hide();
            if (jQuery('#swift_staff_auto_append_job_application_form:checkbox').is(':checked')) {
                if (jQuery.trim(jQuery("#swift_staff_job_application_form_id").val()) === '') {
                    jQuery("#FrmSwiftStaffJobPosts").before('<div id="" class="error emailOptError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://crm.swiftcrm.com/drive/" target="_blank">SwiftCRM.com</a> (free or paid accounts will work) to generate this form.</p></div>');
                    jQuery("#swift_staff_job_application_form_id").focus();
                    e.preventDefault();
                }
            }
        });

        var txt_box;
        jQuery('#pre_ques_body').on('click', '.swiftstaff_preque_add', function() {
            if (jQuery("#pre_ques_body").find('tr').length <= 9) {
                var tmp = jQuery.now();
                if (jQuery('.swiftstaff_preque_min').length <= 0) {
                    jQuery(this).after('<img src="<?php echo plugins_url('../images/delete.png', __FILE__); ?>" alt="Delete" class="swiftstaff_preque_min"/>');
                }
                jQuery(this).remove();
                if (jQuery("#pre_ques_body").find('tr').length == 9) {
                    txt_box = '<tr id="newrow_' + tmp + '" ><td><input type="text" name="swift_staff_pre_interview_questions[]" class="pre_question_txt"/></td><td align="center"><img src="<?php echo plugins_url('../images/delete.png', __FILE__); ?>" alt="Delete" class="swiftstaff_preque_min"/></td></tr>';
                } else {
                    txt_box = '<tr id="newrow_' + tmp + '" ><td><input type="text" name="swift_staff_pre_interview_questions[]" class="pre_question_txt"/></td><td align="center"><img src="<?php echo plugins_url('../images/delete.png', __FILE__); ?>" alt="Delete" class="swiftstaff_preque_min"/><img class="swiftstaff_preque_add" src="<?php echo plugins_url('../images/plus.png', __FILE__); ?>" alt="Add" /></td></tr>';
                }
                jQuery("#pre_ques_body").append(txt_box);
            }
        });

        jQuery('#pre_ques_body').on('click', '.swiftstaff_preque_min', function() {
            var parent_im = jQuery(this).parents('tr').attr("id");
            jQuery("#" + parent_im).slideUp(function() {
                jQuery("#" + parent_im).remove();
                if (jQuery('.swiftstaff_preque_min').length <= 1) {
                    jQuery('.swiftstaff_preque_min').remove();
                }
                if (jQuery('.swiftstaff_preque_add').length < 1) {
                    jQuery('#pre_ques_body tr:last td').eq(1).append('<img src="<?php echo plugins_url('../images/plus.png', __FILE__); ?>" class="swiftstaff_preque_add right" alt="Add" /> ');
                }
            });
        });
    });
</script>