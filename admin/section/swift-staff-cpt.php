<?php
/**
 *      Staff custom post type
 */
$swift_staff_cpt = get_option('swift_staff_cpt');
?>
<div class="wrap">
    <h2 class="sc-page-title">Staff Custom Post Type</h2>
    <hr/>
    <div class="inner_content">
        <form name="FrmSwiftStaffCPT" id="FrmSwiftStaffCPT" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th><label for="swift_staff_cpt">Staff Custom Post Type:</label></th>
                    <td>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_staff_cpt" id="swift_staff_cpt" <?php echo ($swift_staff_cpt == 1 ? 'checked="checked"' : ""); ?>>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <?php wp_nonce_field('save_swift_staff_cpt', 'save_swift_staff_cpt'); ?>
                        <button type="submit" class="button-primary" id="save_swift_staff_cpt_btn" value="swift_staff_settings" name="btn_swift_staff_settings">Save Settings</button>
                    </th>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#swift_staff_cpt:checkbox').rcSwitcher({
            autoFontSize: true
        });
    });
</script>