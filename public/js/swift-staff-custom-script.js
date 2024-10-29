jQuery(document).ready(function () {
    if (jQuery(".swift-staff-page").length > 0) {
        var nav = jQuery("body").find("nav").css("position");
        var header = jQuery("body").find("header").css("position");
        var padding = '';

        if (header === 'fixed' || header === 'absolute') {
            padding = jQuery("body").find("header").height();
        } else if (nav === 'fixed') {
            padding = jQuery("body").find("nav").height();
        }
        if (padding !== '') {
            jQuery(".swift-staff-page").css('padding-top', padding);
        }
    }

    jQuery("#apply_for_this_position").on('click', function () {
        jQuery(".swiftstaff_apply_form").slideToggle('slow');
        jQuery("#apply_for_this_position").hide();
    });

    jQuery('.jobs-pagination-link').tooltipster();

    /* single swift job form */
    jQuery(document).ready(function () {
        if (jQuery('#SC_fh_timezone').length > 0) {
            jQuery('#SC_fh_timezone').val(jstz.determine().name());
        }
        if (jQuery('#SC_fh_capturepage').length > 0) {
            jQuery('#SC_fh_capturepage').val(window.location.origin + window.location.pathname);
        }
        if (jQuery('#SC_fh_language').length > 0) {
            jQuery('#SC_fh_language').val(window.navigator.userLanguage || window.navigator.language);
        }

        jQuery(".swiftstaff_form_apply_now").on("click", function (e) {
            var err = false;
            jQuery(".swiftstaff-error").remove();



            var name = jQuery.trim(jQuery("#FrmswiftStaffApply #swiftstaff_applicant_name").val());
            var email = jQuery.trim(jQuery("#FrmswiftStaffApply #swiftstaff_applicant_email_offdomain").val());
            var phone = jQuery.trim(jQuery("#FrmswiftStaffApply #swiftstaff_applicant_phone").val());
            var swiftstaff_applicant_email = jQuery.trim(jQuery("#FrmswiftStaffApply #swiftstaff_applicant_email").val());

            // for honeypot
            if (swiftstaff_applicant_email.length > 0) {
                err = true;
                return false;
            }

            if (name.length <= 0) {
                jQuery("#FrmswiftStaffApply #swiftstaff_applicant_name").after('<span class="swiftstaff-error">Name is required.</span>');
                err = true;
            }

            if (email.length <= 0) {
                jQuery("#FrmswiftStaffApply #swiftstaff_applicant_email_offdomain").after('<span class="swiftstaff-error">Email is required.</span>');
                err = true;
            } else if (!SwiftStaff_ValidateEmail(email)) {
                jQuery("#FrmswiftStaffApply #swiftstaff_applicant_email_offdomain").after('<span class="swiftstaff-error">Invalid email address.</span>');
                err = true;
            }

            // validate captcha
            if (jQuery("#job_form_captcha_flag").length > 0 && jQuery("#job_form_captcha_flag").val() == 1) {
                var job_captcha_code = jQuery.trim(jQuery("#job_captcha_code").val());

                if (job_captcha_code.length <= 0) {
                    err = true;
                    jQuery("#job_captcha_code-container label").after('<span class="swiftstaff-error">Code is required.</span>');
                    return false;
                } else if (job_captcha_code.toLowerCase() != 'swiftcloud') {
                    err = true;
                    jQuery("#job_captcha_code-container label").after('<span class="swiftstaff-error">Please enter correct code.</span>');
                    return false;
                }
            }

            if (jQuery(".swiftstaff_form_apply_now").attr("data-preque") == 1) {
                err = true;
                jQuery(".preInterview").slideToggle('slow');
                jQuery(".swiftstaff_form_apply_now").attr("data-preque", 0);
                e.preventDefault();
            }

            if (!err && jQuery('#SC_browser').val() !== "WP Fastest Cache Preload Bot") {
                jQuery('#FrmswiftStaffApply #swiftstaff_applicant_email').attr('name', 'BlockThisSender');
                jQuery('#FrmswiftStaffApply #swiftstaff_applicant_email_offdomain').attr('name', 'email');

                var data = {
                    action: 'swift_staff_save_local_capture',
                    name: name,
                    email: email,
                    phone: phone,
                    form_data: jQuery('#FrmswiftStaffApply').serialize()
                };
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: swiftstaff_ajax_object.ajax_url,
                    data: data,
                    beforeSend: function (xhr) {
                        jQuery('.swiftstaff_form_apply_now').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>').attr('disabled', 'disabled');
                    },
                    success: function (response) {
                        if (response.type == "success") {
                            jQuery('.swiftstaff_form_apply_now').after('<span class="swiftstaff_success"><i class="fa fa-check"></i> Your request has been received.</span>');
                            jQuery('#swiftstaff_applicant_name, #swiftstaff_applicant_email, #swiftstaff_applicant_phone, #swiftstaff_applicant_email_offdomain, #swiftstaff_applicant_resume, #job_captcha_code, #FrmswiftStaffApply textarea').val('');
                        } else {
                            jQuery('.swiftstaff_form_apply_now').after('<span class="swiftstaff-error"> Error! while submitting your request.</span>');
                        }
                        jQuery('.swiftstaff_form_apply_now').html('<i class="fa fa-send"></i> &nbsp;Apply Now').removeAttr('disabled');
                    }
                });
            } else {
                return false;
            }
        });
    });

    function showPreInterview() {
        jQuery(".preInterview").slideToggle('slow');
    }
    function SwiftStaff_ValidateEmail(mail)
    {
        if (/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,6}|[0-9]{1,3})(\]?)$/.test(mail))
        {
            return (true);
        }
        return (false);
    }
});