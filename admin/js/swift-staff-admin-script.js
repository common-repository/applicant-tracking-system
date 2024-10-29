jQuery(document).ready(function($) {
    /* tooltip */
    if (jQuery(".ttip").length > 0) {
        jQuery(".ttip").tooltip();
    }
    /* plugin activation notice dismis.*/
    jQuery(".swift-staff-admin-notice .notice-dismiss").on('click', function() {
        var data = {
            'action': 'swift_staff_dismiss_notice'
        };
        jQuery.post(swift_staff_admin_ajax_object.ajax_url, data, function(response) {

        });
    });

    $('.custom-tab').click(function(e) {
        e.preventDefault();
        var $tab = $(this),
                $panel_id = $tab.attr('href');

        /*Remoe active class from all other items*/
        $('.custom-tab').each(function() {
            $(this).removeClass('nav-tab-active');
        });
        /*Add active class to curent*/
        $tab.addClass('nav-tab-active');

        /*Remoe active class from all other panels*/
        $('.panel').each(function() {
            $(this).removeClass('active');
        });
        /*Add active class to curent*/
        $('div' + $panel_id).addClass('active');
    });
});