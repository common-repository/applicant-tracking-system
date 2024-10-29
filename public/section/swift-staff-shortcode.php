<?php

/**
 *      Shortcodes
 */
/**
 *      Shortcode : [swiftstaff_jobs category="category slug"]
 *      - Display list of swift jobs.
 *      - category : Optional;category slug; single or comma separated;  Show jobs in a specific category(s), Default all
 */
add_shortcode('swiftstaff_jobs', 'swift_staff_jobs_callback');
if (!function_exists('swift_staff_jobs_callback')) {

    function swift_staff_jobs_callback($atts) {
        $op = '';
        $a = shortcode_atts(
                array(
            'category' => '',
                ), $atts);
        extract($a);

        $swiftstaff_paged = (get_query_var('paged') ) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'swift_jobs',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'paged' => $swiftstaff_paged,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        $cat_array = !empty($category) ? explode(",", $category) : '';
        if ($cat_array) {
            $args['tax_query'] = array(array('taxonomy' => 'swift_jobs_category', 'field' => 'slug', 'terms' => $cat_array));
        }

        $swift_staff_jobs_list = new WP_Query($args);
        $hiringOrg = get_bloginfo('name');

        $op .= '<div itemscope itemtype="http://schema.org/ItemList"><div class="swift-staff-jobs-wrap"><div class="swift-staff-jobs-content">';
        if ($swift_staff_jobs_list->have_posts()):
            while ($swift_staff_jobs_list->have_posts()) : $swift_staff_jobs_list->the_post();
                $swift_job_location = get_the_term_list(get_the_ID(), 'swiftstaff_location_tag', '', ', ', '');
                $swift_job_location = strip_tags($swift_job_location);

                $op .= '<div itemprop="itemListElement" itemscope itemtype="http://schema.org/JobPosting">
                        <div class="swift-job-wrap">
                            <div class="swift-job-header">
                                <div class="swift-job-title"><a itemprop="url" href="' . get_the_permalink(get_the_ID()) . '"><span itemprop="title">' . get_the_title() . '</span></a></div>
                            </div>
                            <div class="swift-job-content" itemprop="description">
                                <a href="' . get_the_permalink(get_the_ID()) . '">
                                ' . swift_staff_get_excerpt(40) . '
                                </a>
                            </div>
                            <div class="swiftstaff-tags-wrap">
                                ' . get_the_term_list(get_the_ID(), 'swift_jobs_tag', '<ul class="swiftstaff-tags-list"><li>', '</li><li>', '</li></ul>') . '
                            </div>
                            <div class="swift-job-display-none"><span itemprop="datePosted">' . get_the_date('Y-m-d') . '</span></div>
                            <div class="swift-job-display-none"><span itemprop="url">' . get_the_permalink(get_the_ID()) . '</span></div>
                            <div class="swift-job-display-none"><span itemprop="hiringOrganization" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">'. $hiringOrg .'</span></div>
                            <div class="swift-job-display-none"><span itemprop="position"></div>
                            <div class="swift-job-display-none"><span itemprop="jobLocationType">TELECOMMUTE</div>
                            <div class="swift-job-display-none">
                                <span itemprop="jobLocation" itemscope itemtype="http://schema.org/Place">
                                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="addressLocality">' . $swift_job_location .'</span><span itemprop="addressRegion"></span></span>
                                </span>
                            </div>
                          </div>
                      </div>';
            endwhile;
        else :
            $op .= '<div><h3>No jobs found....yet</h3></div>';
        endif;
        $op .= '</div></div></div>';

        wp_reset_postdata();
        return $op;
    }

}


/**
 *      Shortcode : [swift_staff_list]
 *      - Display list of swift staff.
 */
add_shortcode('swift_staff_list', 'swift_staff_list_callback');
if (!function_exists('swift_staff_list_callback')) {

    function swift_staff_list_callback($atts) {
        $op = '';
        $a = shortcode_atts(array(), $atts);
        extract($a);

        $swiftstaff_paged = (get_query_var('paged') ) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'swift_staffs',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'paged' => $swiftstaff_paged,
            'orderby' => 'date',
            'order' => 'ASC'
        );
        $swift_staff_list = new WP_Query($args);

        $op .= '<div class="swift-staff-list-wrapper">';
        if ($swift_staff_list->have_posts()):
            while ($swift_staff_list->have_posts()) : $swift_staff_list->the_post();
                $op .= '<div class="swift-staff-block">';
                if (has_post_thumbnail()) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');
                    $op .= '<div class="staff_avatar_container"><div class="staff_avatar" style="background-image: url(' . $image[0] . ')"><a href="' . get_the_permalink(get_the_ID()) . '"></a></div></div>';
                }
                $op .= '    <div class="' . (has_post_thumbnail() ? "swift-staff-info" : "swift-staff-info-full" ) . '">';
                $op .= '        <div class="swift-staff-title"><h2><a href="' . get_the_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h2></div>';

                if (get_post_meta(get_the_ID(), 'swift_staff_job_title', true)) {
                    $op .= '<div class="swift-staff-job-title"><h4><a href="' . get_the_permalink(get_the_ID()) . '">' . get_post_meta(get_the_ID(), 'swift_staff_job_title', true) . '</a></h4></div>';
                }
                $op .= '        <div class="swift-staff-content">
                                                <a href="' . get_the_permalink(get_the_ID()) . '">
                                                    ' . swift_staff_get_excerpt(50) . '
                                                </a>
                                            </div>
                                        </div>
                                    </div>';
            endwhile;
        else :
            $op .= '<div><h3>No staff list found.</h3></div>';
        endif;
        $op .= '</div>';

        wp_reset_postdata();
        return $op;
    }

}